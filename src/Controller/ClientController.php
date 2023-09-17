<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Utilisateur;
use App\Form\ClientType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/registerclient/{userId}", name="ajoutclient")
     */
    public function ajoutClient($userId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Utilisateur::class);
        $user = $repository->find($userId);
        $client = new Client();

        $form = $this->createForm(ClientType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setUtilisateur($user);
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/ajoutclient.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/client/{id}", name="client")
     */

    public function client($id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Client::class);
        $client = $repository->find($id);

        if (!$client) {
            return $this->redirectToRoute('home');
        }

        return $this->render('client/client.html.twig', [
            'client' => $client,
        ]);
    }
}
