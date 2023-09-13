<?php

namespace App\Entity;

use App\Repository\RechargeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RechargeRepository::class)]

class Recharge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $montant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Reference = null;

    #[ORM\Column(nullable: true)]
    private ?bool $etat = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $texte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Agence = null;

    #[ORM\Column(nullable: true)]
    private ?int $Code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $heure = null;

    #[ORM\ManyToOne(inversedBy: 'recharges')]
    private ?User $utilisePar = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(?int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->Reference;
    }

    public function setReference(?string $Reference): static
    {
        $this->Reference = $Reference;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        // Cette méthode sera appelée avant la persistance de l'entité
        // Vous pouvez initialiser createdAt avec la date du jour actuel
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now'); 
        // Assurez-vous que updatedAt est également mis à jour lors de la création
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(): void
    {
        // Cette méthode sera appelée avant la mise à jour de l'entité
        // Vous pouvez mettre à jour updatedAt avec la date du jour actuel
        $this->updatedAt = new \DateTime('now');
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function getAgence(): ?string
    {
        return $this->Agence;
    }

    public function setAgence(?string $Agence): static
    {
        $this->Agence = $Agence;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->Code;
    }

    public function setCode(?int $Code): static
    {
        $this->Code = $Code;

        return $this;
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

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(?string $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getUtilisePar(): ?User
    {
        return $this->utilisePar;
    }

    public function setUtilisePar(?User $utilisePar): static
    {
        $this->utilisePar = $utilisePar;

        return $this;
    }

}
