<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewsletterRepository::class)
 */
class Newsletter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    private $pdf;

    /**
     * @ORM\Column(type="date")
     */
    private $datepostage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPdf(): ?object
    {
        return $this->pdf;
    }

    public function setPdf(?object $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

    public function getDatepostage(): ?\DateTimeInterface
    {
        return $this->datepostage;
    }

    public function setDatepostage(\DateTimeInterface $datepostage): self
    {
        $this->datepostage = $datepostage;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }
}
