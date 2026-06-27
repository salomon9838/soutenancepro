<?php

namespace App\Entity;

use App\Repository\SoutenanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SoutenanceRepository::class)]
class Soutenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Etudiant::class, inversedBy: 'soutenance')]
    #[ORM\JoinColumn(nullable: false, unique: true)]
    #[Assert\NotNull(message: "L'étudiant est obligatoire.")]
    private ?Etudiant $etudiant = null;

    #[ORM\ManyToOne(targetEntity: Enseignant::class, inversedBy: 'soutenancesPresident')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Le président du jury est obligatoire.')]
    private ?Enseignant $president = null;

    #[ORM\ManyToOne(targetEntity: Enseignant::class, inversedBy: 'soutenancesRapporteur')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Le rapporteur est obligatoire.')]
    private ?Enseignant $rapporteur = null;

    #[ORM\ManyToOne(targetEntity: Enseignant::class, inversedBy: 'soutenancesExaminateur')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "L'examinateur est obligatoire.")]
    private ?Enseignant $examinateur = null;

    #[ORM\ManyToOne(targetEntity: Salle::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'La salle est obligatoire.')]
    private ?Salle $salle = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotNull(message: 'La date est obligatoire.')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'time')]
    #[Assert\NotNull(message: "L'heure est obligatoire.")]
    private ?\DateTimeInterface $heure = null;

    #[ORM\Column(length: 20, options: ['default' => 'programmee'])]
    private string $statut = 'programmee'; // programmee | annulee

    public function getId(): ?int { return $this->id; }

    public function getEtudiant(): ?Etudiant { return $this->etudiant; }
    public function setEtudiant(?Etudiant $etudiant): static { $this->etudiant = $etudiant; return $this; }

    public function getPresident(): ?Enseignant { return $this->president; }
    public function setPresident(?Enseignant $president): static { $this->president = $president; return $this; }

    public function getRapporteur(): ?Enseignant { return $this->rapporteur; }
    public function setRapporteur(?Enseignant $rapporteur): static { $this->rapporteur = $rapporteur; return $this; }

    public function getExaminateur(): ?Enseignant { return $this->examinateur; }
    public function setExaminateur(?Enseignant $examinateur): static { $this->examinateur = $examinateur; return $this; }

    public function getSalle(): ?Salle { return $this->salle; }
    public function setSalle(?Salle $salle): static { $this->salle = $salle; return $this; }

    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): static { $this->date = $date; return $this; }

    public function getHeure(): ?\DateTimeInterface { return $this->heure; }
    public function setHeure(\DateTimeInterface $heure): static { $this->heure = $heure; return $this; }

    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }

    /** @return Enseignant[] */
    public function getMembresJury(): array
    {
        return [$this->president, $this->rapporteur, $this->examinateur];
    }
}
