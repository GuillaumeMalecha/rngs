<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    public const STATUS_COMMAND_PENDING = 'PENDING';
    public const STATUS_COMMAND_PAID = 'PAID';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $datecommande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statuscommande = 'PENDING';

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datepaiement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statuspaiement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $donneepaiement;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="commandes")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=ProduitCommande::class, mappedBy="commandes")
     */
    private $produits_commandes;

    public function __construct()
    {
        $this->produits_commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatecommande(): ?\DateTimeInterface
    {
        return $this->datecommande;
    }

    public function setDatecommande(\DateTimeInterface $datecommande): self
    {
        $this->datecommande = $datecommande;

        return $this;
    }

    public function getStatuscommande(): ?string
    {
        return $this->statuscommande;
    }

    public function setStatuscommande(string $statuscommande): self
    {
        $this->statuscommande = $statuscommande;

        return $this;
    }

    public function getDatepaiement(): ?\DateTimeInterface
    {
        return $this->datepaiement;
    }

    public function setDatepaiement(?\DateTimeInterface $datepaiement): self
    {
        $this->datepaiement = $datepaiement;

        return $this;
    }

    public function getStatuspaiement(): ?string
    {
        return $this->statuspaiement;
    }

    public function setStatuspaiement(string $statuspaiement): self
    {
        $this->statuspaiement = $statuspaiement;

        return $this;
    }

    public function getDonneepaiement(): ?string
    {
        return $this->donneepaiement;
    }

    public function setDonneepaiement(?string $donneepaiement): self
    {
        $this->donneepaiement = $donneepaiement;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
}
