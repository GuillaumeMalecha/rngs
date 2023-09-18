<?php

namespace App\Cart;

use App\Entity\Produit;

class CartItem
{
    public $produit;
    public $quantite;

    public function __construct(Produit $produit, int $quantite)
    {
        $this->produit = $produit;
        $this->quantite = $quantite;
    }

    public function getTotal(): int
    {
        return $this->produit->getPrix() * $this->quantite;
    }

    public function getProduit()
    {
        return $this->produit;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

}