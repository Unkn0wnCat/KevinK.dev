<?php

namespace App\Controller;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    /**
     * @Route("/donate/stripe/{type}/{amount}", defaults={"currency"="EUR"}, requirements={"currency"="EUR|USD", "amount"="\d+", "type"="card|sofort|giropay"}, name="donationStripe")
     * @param int $amount
     * @param string $currency
     * @param string $type
     * @return Response
     */
    public function stripeDonation(int $amount, string $currency, string $type) {
        Stripe::setApiKey($_ENV["STRIPE_PRIVATE_KEY"]);

        $session = Session::create([
            'payment_method_types' => [$type],
            'line_items' => [[
                'name' => 'Donation to KevinK.dev',
                'description' => 'Donation to Kevin Kandlbinder',
                'amount' => $amount,
                'currency' => $currency,
                'quantity' => 1,
            ]],
            'success_url' => $this->generateUrl("donationThanks", [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl("donation", [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->render("donation/donateStripe.html.twig", [
            "CHECKOUT_SESSION_ID" => $session->id,
            "stripe_public_key" => $_ENV["STRIPE_PUBLIC_KEY"]
        ]);
    }
}
