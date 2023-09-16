<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Promotion;
use App\Form\PromotionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends AbstractController
{
    /**
     * @Route("/promotions", name="promotions")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Promotion::class);
        $listePromotions = $repository->findAll();

        if (!$listePromotions) {
            return $this->redirectToRoute('promotion_ajout');
        }

        return $this->render('promotion/index.html.twig', [
            'promotions' => $listePromotions,
            'controller_name' => 'PromotionController',
        ]);
    }

    /**
     * @Route("/detailcategorie/{id}/promotions/ajout", name="promotion_ajout")
     */
    public function ajoutPromotion(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Categorie::class);
        $categorie = $repository->find($id);
        $form = $this->createForm(PromotionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotion = $form->getData();
            $promotion->setCategorie($categorie); // Utilisez setCategorie pour associer la catégorie
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($promotion);
            $entityManager->flush();

            return $this->redirectToRoute('promotions');
        }

        return $this->render('promotion/ajout.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'PromotionController',
            'categorie' => $categorie,
        ]);
    }


}
