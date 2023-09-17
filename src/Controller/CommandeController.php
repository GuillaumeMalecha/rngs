<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommandeController extends AbstractController
{
    /**
     * @Route("/panier/checkout", name="panier_checkout")
     */
    public function checkout(CartService $cartService, EntityManagerInterface $entityManager)
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Créez une nouvelle instance de la commande
        $commande = new Commande();
        $commande->setClient($user);
        $commande->setDateCommande(new \DateTime()); // Définissez la date de la commande

        // Récupérez les produits actuels dans le panier de l'utilisateur
        $panier = $cartService->getDetailPanier();

        // Ajoutez chaque produit du panier à la commande
        foreach ($panier as $item) {
            $produitCommande = new ProduitCommande();
            $produitCommande->setProduit($item['produit']);
            $produitCommande->setQuantite($item['quantite']);
            $produitCommande->setPrix($item['produit']->getPrix()); // Utilisez le prix du produit

            // Assurez-vous de gérer correctement les relations entre Commande et ProduitCommande
            $commande->addProduitsCommande($produitCommande);

            // Supprimez le produit du panier après l'ajout à la commande (facultatif)
            $cartService->delete($item['produit']->getId());
        }

        // Enregistrez la commande dans la base de données
        $entityManager->persist($commande);
        $entityManager->flush();

        // Ajoutez un message de succès
        $this->addFlash('success', 'Votre commande a été passée avec succès.');

        // Redirigez l'utilisateur vers une page de confirmation de commande ou ailleurs
        return $this->redirectToRoute('confirmation_commande', ['id' => $commande->getId()]);
    }


}
