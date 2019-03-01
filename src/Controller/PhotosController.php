<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhotosController
 * @package App\Controller
 * @Route("/photos")
 */
class PhotosController extends AbstractController
{
    /**
     * @Route("/", name="photos")
     */
    public function index()
    {
        return $this->render('photos/index.html.twig', [
            'controller_name' => 'PhotosController',
            'module' => 'photos',
        ]);
    }

    // TODO: Alles.
}
