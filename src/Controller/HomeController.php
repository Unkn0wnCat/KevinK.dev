<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @Route("/sitemap.xml")
     * @param Request $request
     * @return Response
     */
    public function sitemap(Request $request) {
        $urls = [];
        $hostname = $request->getHost();

        $urls[] = ['loc' => $this->get("router")->generate("index"), 'changefreq' => 'weekly', 'priority' => '1.0'];

        $response = new Response();

        $response->headers->set('Content-Type', 'xml');

        return $this->render('sitemap.xml.twig', [
            'urls' => $urls,
            'hostname' => $hostname
        ]);
    }

    /**
     * @Route("/setlanguage/{language}", name="setLang")
     * @param Request $request
     * @param String $language
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function switchLang(Request $request, String $language) {
        $this->get('session')->set('_locale', $language);
        $request->setLocale($language);
        $redirect = $this->get("router")->generate("index");
        if($request->headers->get('referer')) $redirect = $request->headers->get('referer');
        return $this->redirect($redirect);
    }

    /**
     * @Route("/setlanguage", name="chooseLang")
     * @param Request $request
     * @return Response
     */
    public function switchLangChooser(Request $request) {
        return $this->render('home/chooser.html.twig');
    }
}
