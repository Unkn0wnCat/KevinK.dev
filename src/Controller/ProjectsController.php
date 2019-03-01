<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectsController
 * @package App\Controller
 * @Route("/projects")
 */
class ProjectsController extends AbstractController
{
    /**
     * @Route("/", name="projects")
     */
    public function index()
    {
        return $this->render('projects/index.html.twig', [
            'controller_name' => 'ProjectsController',
            'module' => 'projects',
        ]);
    }

    // TODO: Alles.
}
