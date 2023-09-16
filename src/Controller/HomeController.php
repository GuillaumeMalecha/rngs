<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Promotion;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Categorie::class);
        $listeCategories = $repository->findAll();

        $repository = $entityManager->getRepository(Produit::class);
        $listeProduits = $repository->findAll();

        $derniersProduits = $repository->findBy([], ['id' => 'DESC'], 4);

        $repository = $entityManager->getRepository(Promotion::class);
        $listePromotions = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'categories' => $listeCategories,
            'produits' => $listeProduits,
            'promotions' => $listePromotions,
            'derniersProduits' => $derniersProduits,
        ]);
    }

    /**
     * @Route("/recherche", name="recherche")
     */

    public function recherche(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $repository = $entityManager->getRepository(Categorie::class);
        $listeCategories = $repository->findAll();

        $repository = $entityManager->getRepository(Promotion::class);
        $listePromotions = $repository->findAll();

        $formData = $request->request->all();

        $nom = $formData['nom'] ?? null;
        $categorie = $formData['categorie'] ?? null;
        $promotion = $formData['promotion'] ?? null;

        $repository = $entityManager->getRepository(Produit::class);
        $rechercheProduit = $repository->findByRecherche($nom, $categorie, $promotion);

        $pagination = $paginator->paginate(
            $rechercheProduit,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('home/recherche.html.twig', [
            'categories' => $listeCategories,
            'promotions' => $listePromotions,
            'noms' => $nom,
            'rechercheproduit' => $rechercheProduit,
            'pagination' => $pagination,
        ]);
    }
}
