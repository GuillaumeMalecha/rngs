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
}
