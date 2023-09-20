<?php

namespace App\Controller;


use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="app_profil")
     */
    public function profil(Security $security, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'utilisateur connecté
        $user = $security->getUser();

        $client = $user->getClients();

        $repository = $entityManager->getRepository(Commande::class);
        $commandes = $repository->findByClient($client);

        $repository = $entityManager->getRepository(ProduitCommande::class);
        $produitsCommandes = $repository->findByCommande($commandes);

        return $this->render('client/client.html.twig', [
            'client' => $client,
            'commandes' => $commandes,
            'produitsCommandes' => $produitsCommandes,
        ]);
    }
}