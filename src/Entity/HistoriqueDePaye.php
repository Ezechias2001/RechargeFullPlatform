<?php

namespace App\Entity;

use App\Repository\HistoriqueDePayeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueDePayeRepository::class)]
class HistoriqueDePaye
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\ManyToOne(inversedBy: 'historiqueDePayes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $superviseur = null;

    #[ORM\ManyToOne(inversedBy: 'historiqueDePayes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $marchand = null;

    #[ORM\Column]
    private ?bool $statut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getSuperviseur(): ?User
    {
        return $this->superviseur;
    }

    public function setSuperviseur(?User $superviseur): static
    {
        $this->superviseur = $superviseur;

        return $this;
    }

    public function getMarchand(): ?User
    {
        return $this->marchand;
    }

    public function setMarchand(?User $marchand): static
    {
        $this->marchand = $marchand;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
