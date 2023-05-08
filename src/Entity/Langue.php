<?php

namespace App\Entity;

use App\Repository\LangueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LangueRepository::class)
 */
class Langue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=ProduitLangue::class, inversedBy="langues")
     */
    private $produits_langues;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getProduitsLangues(): ?ProduitLangue
    {
        return $this->produits_langues;
    }

    public function setProduitsLangues(?ProduitLangue $produits_langues): self
    {
        $this->produits_langues = $produits_langues;

        return $this;
    }
}
