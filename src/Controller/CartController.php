<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/panier/add/{id}", name="panier_add", requirements={"id"="\d+"})
     */
    public function add($id, ProduitRepository $produitRepository, SessionInterface $session, FlashBagInterface $flashBag)
    {
        $produit = $produitRepository->find($id);

        if(!$produit) {
            throw $this->createNotFoundException("Le produit $id n'existe pas.");
        }

        $panier = $session->get('panier', []);

        if(!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        $flashBag->add('success', 'Le produit a bien été ajouté au panier.');


        return $this->redirectToRoute('detailproduit' , ['id' => $id]);

    }

    /**
     * @Route("/panier", name="panier_show")
     */

    public function show(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $detailPanier = [];
        $total = 0;

        foreach ($session->get('panier', []) as $id => $quantite) {
            $produit = $produitRepository->find($id);
            $detailPanier[] = [
                'produit' => $produit,
                'quantite' => $quantite
            ];

            $total += $produit->getPrix() * $quantite;
        }
        return $this->render('cart/index.html.twig', [
            'items' => $detailPanier,
            'total' => $total

        ]);
    }
}
