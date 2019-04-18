<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use App\Utils\Localize;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class APIController
 * @package App\Controller
 * @Route("/api")
 */
class APIController extends AbstractController
{
    private $localize;

    public function __construct()
    {
        $this->localize = new Localize();
    }

    /**
     * @Route("/blog/{locale}/newest", name="apiBlogNewest")
     * @param BlogPostRepository $blogPostRepository
     * @param string $locale
     * @return Response
     */
    public function apiBlogNewest(BlogPostRepository $blogPostRepository, string $locale)
    {
        $post = $blogPostRepository->findVisiblePosts(1, 0)[0];

        $post = $this->localize->getLocalizedContent($post, $locale);

        return $this->render('api/blogPost.json.twig', [
            "post" => $post,
            "lang" => $locale
        ]);
    }

    /**
     * @Route("/blog/{locale}/{id<[1-9]\d*>}", name="apiBlogRead")
     * @param BlogPost $post
     * @param string $locale
     * @return Response
     */
    public function apiBlogRead(BlogPost $post, string $locale)
    {
        $post = $this->localize->getLocalizedContent($post, $locale);

        return $this->render('api/blogPost.json.twig', [
            "post" => $post,
            "lang" => $locale
        ]);
    }
}
