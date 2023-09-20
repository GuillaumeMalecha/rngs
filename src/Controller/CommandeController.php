<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommandeController extends AbstractController
{
    /**
     * @Route("/panier/checkout", name="panier_checkout", methods={"POST"})
     */

    public function checkout(CartService $cartService, EntityManagerInterface $entityManager, Security $security)
    {
        // Récupérez l'utilisateur connecté
        $user = $security->getUser();

        $client = $user->getClients();

        // Créez une nouvelle instance de la commande
        $commande = new Commande();
        $commande->setClient($client);
        $commande->setDateCommande(new \DateTime('now'));
        $commande->setStatuspaiement('en attente');
        $commande->setStatuscommande('en cours');

        // Récupérez les produits actuels dans le panier de l'utilisateur
        $panier = $cartService->getDetailPanier();


        // Ajoutez chaque produit du panier à la commande
        foreach ($panier as $cartItem) {
            $produitCommande = new ProduitCommande();
            $produitCommande->setProduit($cartItem->getProduit());
            $produitCommande->setQuantite($cartItem->getQuantite());
            $produitCommande->setPrix($cartItem->getProduit()->getPrix());
            $produitCommande->setCommande($commande);
            $produitCommande->setProduitNom($cartItem->getProduit()->getNom());

            $entityManager->persist($produitCommande);

            // Assurez-vous de gérer correctement les relations entre Commande et ProduitCommande
            $commande->addProduitsCommande($produitCommande);

            // Supprimez le produit du panier après l'ajout à la commande (facultatif)
            //$cartService->delete($cartItem->getProduit()->getId());

        }

        // Enregistrez la commande dans la base de données
        $entityManager->persist($commande);
        $entityManager->flush();

        // Ajoutez un message de succès
        $this->addFlash('success', 'Votre commande a été passée avec succès.');

        // Redirigez l'utilisateur vers une page de confirmation de commande ou ailleurs
        return $this->redirectToRoute('confirmation_commande', ['id' => $commande->getId()]);
    }

    /**
     * @Route("/commande/{id}", name="confirmation_commande")
     */

    public function confirmationCommande($id, EntityManagerInterface $entityManager, Commande $commande): Response
    {

        if (!$commande) {
            return $this->redirectToRoute('home');
        }

        // Calcul du total de la commande
        $totalCommande = 0;
        foreach ($commande->getProduitsCommandes() as $produitCommande) {
            $totalCommande += $produitCommande->getPrix() * $produitCommande->getQuantite();
        }

        return $this->render('commande/confirmation.html.twig', [
            'commande' => $commande,
            'totalCommande' => $totalCommande,
        ]);
    }


}
