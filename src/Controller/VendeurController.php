<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Vendeur;
use App\Form\RegistrationType;
use App\Form\VendeurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendeurController extends AbstractController
{
    /**
     * @Route("/registervendeur/{userId}", name="ajoutvendeur")
     */

    public function ajoutVendeur($userId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Utilisateur::class);
        $user = $repository->find($userId);
        $vendeur = new Vendeur();

        $form = $this->createForm(VendeurType::class, $vendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vendeur->setUtilisateur($user);
            $entityManager->persist($vendeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/ajoutvendeur.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vendeur/{id}", name="vendeur")
     */

    public function vendeur($id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Vendeur::class);
        $vendeur = $repository->find($id);

        if (!$vendeur) {
            return $this->redirectToRoute('home');
        }

        return $this->render('vendeur/vendeur.html.twig', [
            'vendeur' => $vendeur,
        ]);
    }
}
