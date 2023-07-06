<?php

namespace App\Controller;

use App\Entity\Categorie;
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

        return $this->render('categorie/index.html.twig', [
            'categories' => $listeCategories,
        ]);
    }

    /**
     * @Route("/ajoutcategorie", name="ajoutcategorie")
     */

    public function ajoutcategorie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();
            return $this->redirectToRoute('toutescategories');
        }

        return $this->render('categorie/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
