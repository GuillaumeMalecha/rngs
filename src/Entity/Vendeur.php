<?php

namespace App\Entity;

use App\Repository\VendeurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VendeurRepository::class)
 */
class Vendeur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroemploye;

    /**
     * @ORM\OneToOne(targetEntity=Utilisateur::class, mappedBy="vendeurs", cascade={"persist", "remove"})
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroemploye(): ?int
    {
        return $this->numeroemploye;
    }

    public function setNumeroemploye(int $numeroemploye): self
    {
        $this->numeroemploye = $numeroemploye;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        // unset the owning side of the relation if necessary
        if ($utilisateur === null && $this->utilisateur !== null) {
            $this->utilisateur->setVendeurs(null);
        }

        // set the owning side of the relation if necessary
        if ($utilisateur !== null && $utilisateur->getVendeurs() !== $this) {
            $utilisateur->setVendeurs($this);
        }

        $this->utilisateur = $utilisateur;

        return $this;
    }
}
