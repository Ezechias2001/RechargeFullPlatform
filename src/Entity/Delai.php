<?php

namespace App\Entity;

use App\Repository\DelaiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DelaiRepository::class)]
class Delai
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date = null;

    #[ORM\OneToOne(inversedBy: 'delai', cascade: ['persist', 'remove'])]
    private ?User $fils = null;

    #[ORM\Column(nullable: true)]
    private ?bool $respecte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getFils(): ?User
    {
        return $this->fils;
    }

    public function setFils(?User $fils): static
    {
        $this->fils = $fils;

        return $this;
    }

    public function isRespecte(): ?bool
    {
        return $this->respecte;
    }

    public function setRespecte(?bool $respecte): static
    {
        $this->respecte = $respecte;

        return $this;
    }
}
