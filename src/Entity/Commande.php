<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
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
    private $statuscommande;

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
}
