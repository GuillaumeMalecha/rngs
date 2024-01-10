<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Image;
use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_VENDEUR")
     */

    public function ajoutproduit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nomProduit = $form->get('nom')->getData();

            // Vérification que le produit n'existe pas déjà
            $existingProduit = $entityManager->getRepository(Produit::class)->findOneBy(['nom' => $nomProduit]);

            if ($existingProduit) {
                $this->addFlash('danger', 'Le produit ' . $nomProduit . ' existe déjà.');
                return $this->redirectToRoute('ajoutproduit');
            }

            // Vérification du prix
            $prix = $produit->getPrix();
            if ($prix <= 0) {
                $this->addFlash('danger', 'Le prix du produit doit être supérieur à zéro.');
                return $this->redirectToRoute('ajoutproduit');
            }

            // Traitement des images
            $imagesFiles = $request->files->get('produit')['images_files'];
            if (!empty($imagesFiles)) {
                foreach ($imagesFiles as $imageFile) {
                    $image = new Image();
                    $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                    $image->setNom($fileName);
                    $produit->addImage($image);
                    $entityManager->persist($image);
                }
            }

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

        if (!$produit) {
            throw $this->createNotFoundException('Le produit avec l\'identifiant ' . $id . ' n\'existe pas.');
        }

        $repository = $entityManager->getRepository(Categorie::class);
        $categorie = $repository->find(['id' => $produit->getCategorie()]);

        // Vérifiez s'il y a une promotion active pour cette catégorie
        $promotion = $categorie->getPromotions()->first(); // Vous pouvez ajuster cette logique pour gérer plusieurs promotions par catégorie

        if ($promotion && $promotion->getDatedebut() <= new \DateTime('today') && $promotion->getDatefin() >= new \DateTime('today')) {
            // Promotion active, calculez le nouveau prix du produit
            $pourcentageReduction = $promotion->getPourcentage();
            $prixInitial = $produit->getPrix();
            $nouveauPrix = $prixInitial - ($prixInitial * ($pourcentageReduction / 100));
            $produit->setPrix($nouveauPrix);
        }

        return $this->render('produit/detailproduit.html.twig', [
            'produit' => $produit,
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/detailproduit/{id}/supprimer", name="supprimerproduit")
     */

    public function supprimerproduit(int $id, EntityManagerInterface $entityManager): Response
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
