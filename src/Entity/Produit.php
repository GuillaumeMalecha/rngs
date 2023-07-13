<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
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
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $datesortie;

    /**
     * @ORM\ManyToMany(targetEntity=ProduitCommande::class, inversedBy="produits")
     */
    private $produits_commandes;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="produit")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=ProduitLangue::class, mappedBy="produit")
     */
    private $produits_langues;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="produits")
     */
    private $promotion;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    public function __construct()
    {
        $this->produits_commandes = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->produits_langues = new ArrayCollection();
    }

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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDatesortie(): ?\DateTimeInterface
    {
        return $this->datesortie;
    }

    public function setDatesortie(\DateTimeInterface $datesortie): self
    {
        $this->datesortie = $datesortie;

        return $this;
    }

    /**
     * @return Collection<int, produitcommande>
     */
    public function getProduitsCommandes(): Collection
    {
        return $this->produits_commandes;
    }

    public function addProduitsCommande(produitcommande $produitsCommande): self
    {
        if (!$this->produits_commandes->contains($produitsCommande)) {
            $this->produits_commandes[] = $produitsCommande;
        }

        return $this;
    }

    public function removeProduitsCommande(produitcommande $produitsCommande): self
    {
        $this->produits_commandes->removeElement($produitsCommande);

        return $this;
    }

    /**
     * @return Collection<int, image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduit($this);
        }

        return $this;
    }

    public function removeImage(image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduit() === $this) {
                $image->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProduitLangue>
     */
    public function getProduitsLangues(): Collection
    {
        return $this->produits_langues;
    }

    public function addProduitsLangue(ProduitLangue $produitsLangue): self
    {
        if (!$this->produits_langues->contains($produitsLangue)) {
            $this->produits_langues[] = $produitsLangue;
            $produitsLangue->setProduit($this);
        }

        return $this;
    }

    public function removeProduitsLangue(ProduitLangue $produitsLangue): self
    {
        if ($this->produits_langues->removeElement($produitsLangue)) {
            // set the owning side to null (unless already changed)
            if ($produitsLangue->getProduit() === $this) {
                $produitsLangue->setProduit(null);
            }
        }

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
