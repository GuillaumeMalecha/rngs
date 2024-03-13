<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PaymentController extends AbstractController
{
    /**
     * @Route("/panier/payement/{id}", name="panier_payement", methods={"POST"})
     */
    public function showCardForm()
    {
        return $this->render('commande/payement.html.twig');
    }

}