<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="tousproduits")
     */
    public function tousproduits(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $repository = $entityManager->getRepository(Produit::class);
        $listeProduits = $repository->findAll();

        $pagination = $paginator->paginate(
            $listeProduits,
            $request->query->getInt('page', 1),
            5
        );

        if (!$listeProduits) {
            return $this->redirectToRoute('ajoutproduit');
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $listeProduits,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/ajoutproduit", name="ajoutproduit")
     */

    public function ajoutproduit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('tousproduits');
        }

        return $this->renderForm('produit/ajoutproduit.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/detailproduit/{id}", name="detailproduit")
     */

    public function detailproduit($id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Produit::class);
        $produit = $repository->find($id);

        $repository = $entityManager->getRepository(Categorie::class);
        $categorie = $repository->find(['id' => $produit->getCategorie()]);

        return $this->render('produit/detailproduit.html.twig', [
            'produit' => $produit,
            'categorie' => $categorie
        ]);
    }

    /**
     * @Route("//detailproduit/{id}/supprimer", name="supprimerproduit")
     */

    public function supprimerproduit(int $id, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Produit::class);
        $produit = $repository->find($id);

        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('tousproduits');
    }

    /**
     * @Route("/detailproduit/{id}/modifier", name="modifierproduit")
     */

    public function modifierproduit(int $id, Request $request, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Produit::class);
        $produit = $repository->find($id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('tousproduits');
        }
        return $this->renderForm('produit/modifierproduit.html.twig', [
            'form' => $form,
            'produit' => $produit
        ]);
    }
}
