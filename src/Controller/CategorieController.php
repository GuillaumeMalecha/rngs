<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Promotion;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="toutescategories")
     */
    public function toutescategories(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Categorie::class);
        $listeCategories = $repository->findAll();

        if (!$listeCategories) {
            return $this->redirectToRoute('ajoutcategorie');
        }

        // Créez un tableau pour stocker les catégories avec promotions actives
        $categoriesAvecPromotion = [];

        foreach ($listeCategories as $categorie) {
            // Vérifiez s'il y a une promotion active pour cette catégorie
            $promotion = $categorie->getPromotions()->first(); // Vous pouvez ajuster cette logique pour gérer plusieurs promotions par catégorie

            if ($promotion && $promotion->getDatedebut() <= new \DateTime('today') && $promotion->getDatefin() >= new \DateTime('today')) {
                // Promotion active, ajoutez la catégorie au tableau
                $categoriesAvecPromotion[] = [
                    'categorie' => $categorie,
                    'promotion' => $promotion,
                ];
            }
        }

        return $this->render('categorie/index.html.twig', [
            'categories' => $listeCategories,
            'categoriesAvecPromotion' => $categoriesAvecPromotion, // Passez les catégories avec promotions à votre modèle Twig
        ]);
    }


    /**
     * @Route("/ajoutcategorie", name="ajoutcategorie")
     * @IsGranted("ROLE_VENDEUR", message="Vous devez être vendeur pour accéder à cette page")
     */

    public function ajoutcategorie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nomCategorie = $form->get('nom')->getData();

            // Vérification que le nom de la catégorie n'existe pas déjà
            $existingCategorie = $entityManager->getRepository(Categorie::class)->findOneBy(['nom' => $nomCategorie]);

            if ($existingCategorie) {
                $this->addFlash('danger', 'Cette catégorie existe déjà.');
                return $this->redirectToRoute('ajoutcategorie');
            }

            // Si le nom de la catégorie est unique, continuez avec la création de la catégorie
            $entityManager->persist($categorie);
            $entityManager->flush();
            return $this->redirectToRoute('toutescategories');
        }

        return $this->render('categorie/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/detailcategorie/{id}", name="detailcategorie")
     */

    public function detailcategorie($id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Categorie::class);
        $categorie = $repository->find($id);



        if (!$categorie) {
            throw $this->createNotFoundException(
                'Aucune catégorie trouvée avec le numéro ' . $id
            );
        }

        $promotions = $categorie->getPromotions();
        $produits = $categorie->getProduits();


        return $this->render('categorie/detail.html.twig', [
            'categorie' => $categorie,
            'promotions' => $promotions,
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/detailcategorie/{id}/modifier", name="modifiercategorie")
     * @IsGranted("ROLE_VENDEUR", message="Vous devez être vendeur pour accéder à cette page")
     */

    public function modifiercategorie(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $repository = $entityManager->getRepository(Categorie::class);
        $categorie = $repository->find($id);
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('toutescategories');
        }

        return $this->renderForm('categorie/modifier.html.twig', [
            'form' => $form,
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/detailcategorie/{id}/supprimer", name="supprimercategorie")
     * @IsGranted("ROLE_ADMIN", message="Vous devez être admin pour accéder à cette page")
     */

    public function supprimercategorie(int $id, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Categorie::class);
        $categorie = $repository->find($id);
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('toutescategories');
    }

}
