<?php

namespace App\Entity;

use App\Repository\DemandeDeValidationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeDeValidationRepository::class)]
class DemandeDeValidation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\ManyToOne(inversedBy: 'demandeDeValidations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Demandeur = null;

    #[ORM\Column]
    private ?bool $etat = null;

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

    public function getDemandeur(): ?User
    {
        return $this->Demandeur;
    }

    public function setDemandeur(?User $Demandeur): static
    {
        $this->Demandeur = $Demandeur;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }
}
