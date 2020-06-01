<?php

namespace App\Controller;

use App\Repository\BlogPostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin", name="admin_")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
        ]);
    }

    /**
     * @Route("/blog/list", name="blog_list")
     * @param BlogPostRepository $blogPostRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function blogPostList(BlogPostRepository $blogPostRepository)
    {
        return $this->render('admin/blogPostList.html.twig', [
            "posts" => $blogPostRepository->findBy([], ["publishTime" => "ASC"])
        ]);
    }
}
