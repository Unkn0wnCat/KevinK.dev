<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'module' => 'blog',
        ]);
    }

    // TODO: Alles.
}
