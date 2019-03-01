<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            "module" => "home",
        ]);
    }

    /**
     * @Route("/legal/about", name="impressum")
     */
    public function impressum()
    {
        return $this->render('home/impressum.html.twig', [

        ]);
    }

    /**
     * @Route("/legal/datasec", name="datenschutz")
     */
    public function datenschutz()
    {
        return $this->render('home/datasec.html.twig', [

        ]);
    }
}
