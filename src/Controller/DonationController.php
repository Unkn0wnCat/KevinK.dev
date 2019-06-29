<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DonationController
 * @package App\Controller
 * @Route("/payments")
 */
class DonationController extends AbstractController
{
    /**
     * @Route("/donate", name="donation")
     */
    public function donate()
    {
        return $this->render('donation/donate.html.twig', [
            'module' => 'donation',
        ]);
    }
    /**
     * @Route("/donate/thank-you", name="donationThanks")
     */
    public function donationThanks()
    {
        return $this->render('donation/donationComplete.html.twig', [
            'module' => 'donation',
        ]);
    }
}
