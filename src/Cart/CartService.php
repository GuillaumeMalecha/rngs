<?php

namespace App\Cart;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $produitRepository;

    public function __construct(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $this->session = $session;
        $this->produitRepository = $produitRepository;
    }

    public function add(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

    public function delete(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }

    public function decrement(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]--;
        }

        if ($panier[$id] <= 0) {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->session->get('panier', []) as $id => $quantite) {
            $produit = $this->produitRepository->find($id);

            if (!$produit) {
                continue;
            }

            $total += $produit->getPrix() * $quantite;
        }

        return $total;
    }

    public function getDetailPanier(): array
    {
        $detailPanier = [];

        foreach ($this->session->get('panier', []) as $id => $quantite) {
            $produit = $this->produitRepository->find($id);

            if (!$produit) {
                continue;
            }

            $detailPanier[] = new CartItem($produit, $quantite);
        }

        return $detailPanier;
    }


    public
    function remove(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }
}