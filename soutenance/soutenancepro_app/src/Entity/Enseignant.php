<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
class Enseignant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire.')]
    private ?string $prenom = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email n'est pas valide.")]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'La spécialité est obligatoire.')]
    private ?string $specialite = null;

    #[ORM\OneToOne(mappedBy: 'enseignant', targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'president', targetEntity: Soutenance::class)]
    private Collection $soutenancesPresident;

    #[ORM\OneToMany(mappedBy: 'rapporteur', targetEntity: Soutenance::class)]
    private Collection $soutenancesRapporteur;

    #[ORM\OneToMany(mappedBy: 'examinateur', targetEntity: Soutenance::class)]
    private Collection $soutenancesExaminateur;

    public function __construct()
    {
        $this->soutenancesPresident = new ArrayCollection();
        $this->soutenancesRapporteur = new ArrayCollection();
        $this->soutenancesExaminateur = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(string $prenom): static { $this->prenom = $prenom; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }

    public function getSpecialite(): ?string { return $this->specialite; }
    public function setSpecialite(string $specialite): static { $this->specialite = $specialite; return $this; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }

    /** Toutes les soutenances où cet enseignant intervient (président, rapporteur ou examinateur) */
    public function getToutesSoutenances(): array
    {
        return array_merge(
            $this->soutenancesPresident->toArray(),
            $this->soutenancesRapporteur->toArray(),
            $this->soutenancesExaminateur->toArray()
        );
    }

    public function __toString(): string { return $this->prenom.' '.$this->nom; }
}
