<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Stripe\Stripe;
use Stripe\Checkout\Session;


// Payement obsolète à supprimer par après
/*class PaymentController extends AbstractController
{
    /**
     * @Route("/panier/payement", name="payement_form", methods={"GET"})
     */
 /*  public function showPaymentForm(CartService $cartService)
    {
        $detailPanier = $cartService->getDetailPanier();
        $total = $cartService->getTotal();
        return $this->render('commande/payement.html.twig', [
            'items' => $detailPanier,
            'total' => $total
        ]);
    }*/


// Payement avec Stripe avec redirection vers la page de paiement de Stripe
class PaymentController extends AbstractController
{
    /**
     * @Route("/panier/payement", name="payement_form", methods={"GET"})
     */
    public function showPaymentForm(CartService $cartService)
    {
        $total = $cartService->getTotal(); // Récupérer le montant total de la commande

        // Créer une session de paiement avec Stripe Checkout
        Stripe::setApiKey('sk_test_51OlBvSEf0soCt9LMA7V66o4ATjCBOhInP6wTlwRdnhhEJjALJsoo2boojQSlzYb422twwfRpxgCVUfc2nnrk0qT200FlXNhUgR');
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Votre commande sur RNGS',
                    ],
                    'unit_amount' => $total,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('commande_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('panier_show', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        // Rediriger vers la page de paiement de Stripe avec l'ID de session de paiement
        return new RedirectResponse($session->url, RedirectResponse::HTTP_TEMPORARY_REDIRECT);
    }

}
