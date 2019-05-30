<?php

namespace App\Controller;

use App\Entity\BlogCategory;
use App\Entity\BlogPost;
use App\Form\EditPostType;
use App\Repository\BlogPostRepository;
use App\Utils\Localize;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    protected $postsPerPage = 10;
    private $localize;

    public function __construct()
    {
        $this->localize = new Localize();
    }

    /**
     * @Route("/", defaults={"page": "1"}, name="blogHome")
     * @Route("/{page<[0-9]\d*>}", name="blogPage")
     * @param BlogPostRepository $blogPostRepository
     * @param int $page
     * @param Request $request
     * @param bool $isHome
     * @return Response
     */
    public function blogPage(BlogPostRepository $blogPostRepository, int $page, Request $request, $isHome = false) {
        $offset = ($page - 1) * $this->postsPerPage;

        if($offset < 0) {
            $offset = 0;
        }

        $count = $blogPostRepository->count(["visible" => true]);
        $pageCount = ceil($count / $this->postsPerPage);

        if($page > $pageCount) {
            return $this->redirectToRoute("blogPage",[
                "page" => $pageCount
            ]);
        }

        $postList = $blogPostRepository->findVisiblePosts($this->postsPerPage, $offset);

        foreach ($postList as $key => $post) {
            $postList[$key] = $this->localize->getLocalizedContent($post, $request->getLocale());
        }

        return $this->render('blog/index.html.twig', [
            'module' => 'blog',
            'isHome' => $isHome,
            'posts' => $postList,
            'pageCount' => $pageCount,
            'page' => $page
        ]);
    }

    /**
     * @Route("/read/{post_id}", name="blogViewDirect", defaults={"year": "0", "month": "0", "day": "0", "canonical": "aaaaa"})
     * @Route("/{year}/{month}/{day}/{post_id}-{canonical}", name="blogView")
     * @param Request $request
     * @param BlogPostRepository $blogPostRepository
     * @param $year
     * @param $month
     * @param $day
     * @param $post_id
     * @param $canonical
     * @return Response
     */
    public function blogView(Request $request, BlogPostRepository $blogPostRepository, $year, $month, $day, $post_id, $canonical) {
        $post = $blogPostRepository->findOneBy([
            "id" => $post_id
        ]);

        $post = $this->localize->getLocalizedContent($post, $request->getLocale());

        $canonicalGen = $post->getCanonicalName();

        if(date("Y/m/d", $post->getPublishTime()->getTimestamp()) != sprintf("%s/%s/%s", $year, $month, $day) || $canonicalGen != $canonical) {
            return $this->redirectToRoute("blogView", [
               "post_id" => $post_id,
                "day" =>  date("d", $post->getPublishTime()->getTimestamp()),
                "month" =>  date("m", $post->getPublishTime()->getTimestamp()),
                "year" =>  date("Y", $post->getPublishTime()->getTimestamp()),
                "canonical" => $canonicalGen
            ]);
        }

        return $this->render('blog/read.html.twig', [
           'module' => 'blog',
           'post' => $post
        ]);
    }

    /**
     * @Route("/category/{urlName}", name="blogCategory", defaults={"page": "1"})
     * @Route("/category/{urlName}/{page}", name="blogCategoryPage")
     * @param BlogCategory $category
     * @param int $page
     * @param Request $request
     * @return Response
     */
    public function blogCategoryPage(BlogCategory $category, int $page, Request $request) {
        $offset = ($page - 1) * $this->postsPerPage;

        $postRepository = $this->getDoctrine()->getRepository(BlogPost::class);

        $count = $postRepository->count(["visible" => true, "category" => $category]);
        $pageCount = ceil($count / $this->postsPerPage);

        $postList = $postRepository->findVisiblePostsByCategory($category, $this->postsPerPage, $offset);

        foreach ($postList as $key => $post) {
            $postList[$key] = $this->localize->getLocalizedContent($post, $request->getLocale());
        }


        return $this->render('blog/category.html.twig', [
            'module' => 'blog',
            'posts' => $postList,
            'pageCount' => $pageCount,
            'page' => $page,
            'category' => $category
        ]);
    }

    /**
     * @Route("/editor/new", name="blogPostNew", defaults={"postID": "-1"})
     * @Route("/editor/edit/{postID}", name="blogPostEdit")
     * @param BlogPostRepository $blogPostRepository
     * @param Request $request
     * @param int $postID
     * @return Response
     */
    public function blogPostEditor(BlogPostRepository $blogPostRepository, Request $request, int $postID) {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $form = $this->createForm(EditPostType::class);


        if($postID == -1) {
            $post = new BlogPost();
            $post->setAuthor($this->getUser());
        } else {
            $post = $blogPostRepository->findOneBy([
                "id" => $postID
            ]);
            if($post == null) {
                throw new NotFoundHttpException();
            }
        }

        $post->setTitle(json_encode($post->getTitle()));
        $post->setSummary(json_encode($post->getSummary()));
        $post->setContent(json_encode($post->getContent()));

        $form->setData($post);

        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $post->setTitle(json_decode($post->getTitle(), true));
            $post->setSummary(json_decode($post->getSummary(), true));
            $post->setContent(json_decode($post->getContent(), true));

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute("blogViewDirect", ["post_id" => $post->getId()]);
        }


        return $this->render('blog/editor.html.twig', [
            'module' => 'blog',
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}
