<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SocialLinkRepository;

/**
 * @Route("/social", name="social_")
 */
class SocialController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SocialLinkRepository $repo)
    {
        return $this->render('social/index.html.twig', [
            'module' => 'social',
            'socials' => $repo->findBy([], ["orderPriority"=>"ASC", "id"=>"ASC"])
        ]);
    }
}
