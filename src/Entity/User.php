<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $FullName = null;

    #[ORM\Column(nullable: true)]
    private ?int $PhoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Agence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ProfilePicture = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'users')]
    private ?self $createur = null;

    #[ORM\OneToMany(mappedBy: 'createur', targetEntity: self::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'utilisePar', targetEntity: Recharge::class)]
    private Collection $recharges;

    #[ORM\Column(nullable: true)]
    private ?int $Dette = null;

    #[ORM\Column(nullable: true)]
    private ?int $Marge = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->recharges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(string $role): static
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFullName(): ?string
    {
        return $this->FullName;
    }

    public function setFullName(string $FullName): static
    {
        $this->FullName = $FullName;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->PhoneNumber;
    }

    public function setPhoneNumber(?int $PhoneNumber): static
    {
        $this->PhoneNumber = $PhoneNumber;

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

    public function getProfilePicture(): ?string
    {
        return $this->ProfilePicture;
    }

    public function setProfilePicture(?string $ProfilePicture): static
    {
        $this->ProfilePicture = $ProfilePicture;

        return $this;
    }

    public function getCreateur(): ?self
    {
        return $this->createur;
    }

    public function setCreateur(?self $createur): static
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(self $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCreateur($this);
        }

        return $this;
    }

    public function removeUser(self $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCreateur() === $this) {
                $user->setCreateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recharge>
     */
    public function getRecharges(): Collection
    {
        return $this->recharges;
    }

    public function addRecharge(Recharge $recharge): static
    {
        if (!$this->recharges->contains($recharge)) {
            $this->recharges->add($recharge);
            $recharge->setUtilisePar($this);
        }

        return $this;
    }

    public function removeRecharge(Recharge $recharge): static
    {
        if ($this->recharges->removeElement($recharge)) {
            // set the owning side to null (unless already changed)
            if ($recharge->getUtilisePar() === $this) {
                $recharge->setUtilisePar(null);
            }
        }

        return $this;
    }

    public function getDette(): ?int
    {
        return $this->Dette;
    }

    public function setDette(?int $Dette): static
    {
        $this->Dette = $Dette;

        return $this;
    }

    public function getMarge(): ?int
    {
        return $this->Marge;
    }

    public function setMarge(?int $Marge): static
    {
        $this->Marge = $Marge;

        return $this;
    }
}
