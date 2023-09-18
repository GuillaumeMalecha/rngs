<?php

namespace App\Entity;

use App\Repository\ProduitCommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitCommandeRepository::class)
 */
class ProduitCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="produits_commandes")
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="produits_commandes")
     */
    private $produit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $produitNom;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }


    public function getProduit(): Produit
    {
        return $this->produit;

    }

    public function setProduit(Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getProduitNom(): ?string
    {
        return $this->produitNom;
    }

    public function setProduitNom(string $produitNom): self
    {
        $this->produitNom = $produitNom;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

}
