<?php

namespace App\Entity;

use App\Repository\ProduitLangueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitLangueRepository::class)
 */
class ProduitLangue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $traduction;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="produits_langues")
     */
    private $produit;

    /**
     * @ORM\OneToMany(targetEntity=Langue::class, mappedBy="produits_langues")
     */
    private $langues;

    public function __construct()
    {
        $this->langues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTraduction(): ?string
    {
        return $this->traduction;
    }

    public function setTraduction(?string $traduction): self
    {
        $this->traduction = $traduction;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * @return Collection<int, Langue>
     */
    public function getLangues(): Collection
    {
        return $this->langues;
    }

    public function addLangue(Langue $langue): self
    {
        if (!$this->langues->contains($langue)) {
            $this->langues[] = $langue;
            $langue->setProduitsLangues($this);
        }

        return $this;
    }

    public function removeLangue(Langue $langue): self
    {
        if ($this->langues->removeElement($langue)) {
            // set the owning side to null (unless already changed)
            if ($langue->getProduitsLangues() === $this) {
                $langue->setProduitsLangues(null);
            }
        }

        return $this;
    }
}
