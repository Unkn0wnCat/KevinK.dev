<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FriendRepository;

/**
 * @Route("/friends", name="friends_")
 */
class FriendsController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(FriendRepository $friends)
    {
        return $this->render('friends/index.html.twig', [
            "module" => "friends",
            "friends" => $friends->findAll()
        ]);
    }
}
