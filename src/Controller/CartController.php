<?php

namespace App\Controller;

use App\Cart\CartService;
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
    public function add($id, ProduitRepository $produitRepository, CartService $cartService, FlashBagInterface $flashBag, Request $request)
    {
        $produit = $produitRepository->find($id);

        if(!$produit) {
            throw $this->createNotFoundException("Le produit $id n'existe pas.");
        }

        $cartService->add($id);

        $flashBag->add('success', 'Le produit a bien été ajouté au panier.');

        if($request->query->get('returnToCart')) {
            return $this->redirectToRoute('panier_show');
        }

        return $this->redirectToRoute('detailproduit' , ['id' => $id]);

    }

    /**
     * @Route("/panier", name="panier_show")
     */

    public function show(CartService $cartService)
    {
        $detailPanier = $cartService->getDetailPanier();

        $total = $cartService->getTotal();

        return $this->render('cart/index.html.twig', [
            'items' => $detailPanier,
            'total' => $total
        ]);


    }

    /**
     * @Route("/panier/delete/{id}", name="panier_delete", requirements={"id"="\d+"})
     */

    public function delete($id, ProduitRepository $produitRepository, CartService $cartService, FlashBagInterface $flashBag)
    {
        $produit = $produitRepository->find($id);

        if(!$produit) {
            throw $this->createNotFoundException("Le produit $id n'existe pas.");
        }

        $cartService->delete($id);

        $flashBag->add('success', 'Le produit a bien été supprimé du panier.');

        return $this->redirectToRoute('panier_show');
    }

    /**
     * @Route("/panier/decrement/{id}", name="panier_decrement", requirements={"id"="\d+"})
     */

    public function decrement($id, ProduitRepository $produitRepository, CartService $cartService, FlashBagInterface $flashBag)
    {
        $produit = $produitRepository->find($id);

        if(!$produit) {
            throw $this->createNotFoundException("Le produit $id n'existe pas.");
        }

        $cartService->decrement($id);

        $flashBag->add('success', 'La quantité du produit a bien été diminuée.');

        return $this->redirectToRoute('panier_show');
    }
}
