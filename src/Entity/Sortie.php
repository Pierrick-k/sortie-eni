<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Le nom de la sortie doit être renseigné')]
    #[Assert\Length(min: 3, max: 100,
        minMessage: 'Le nom de la sortie doit être supérieur à 2 caractères',
        maxMessage: 'Le nom de la sortie doit être inférieur à 255 caractères')]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\GreaterThan(value: 'tomorrow', message: 'La date doit être au moins un jour après celle d\'aujourd\'hui.')]
    #[Assert\NotBlank(message: 'La date et heure de début doit être renseigné')]
    private ?\DateTimeImmutable $dateHeureDebut = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'La durée doit être renseigné')]
    #[Assert\Range(notInRangeMessage: 'La durée doit être entre 1 et 1440 minutes', min: 1,
        max: 1440)]
    private ?int $duree = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La date limite d\'inscription doit être renseignée')]
    #[Assert\GreaterThan(value: 'tomorrow', message: 'La date doit être au moins un jour après celle d\'aujourd\'hui.')]
    private ?\DateTimeImmutable $dateLimiteInscription = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Le nombre d\'incription maximum doit être renseigné')]
    private ?int $nbInscriptionMax = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 5, max: 255,
        minMessage: 'Les infos doivent avoir minimum 5 caractères',
        maxMessage: 'Les infos doivent être en dessous de 255 caractères')]
    private ?string $infosSortie = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    #[Assert\NotBlank(message: 'Le campus doit être renseigné')]
    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[Assert\NotBlank(message: 'La lieu de la sortie doit être renseigné')]
    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organisateur = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'sortiesPart')]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeImmutable
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeImmutable $dateHeureDebut): static
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeImmutable
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeImmutable $dateLimiteInscription): static
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): static
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): static
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }


    #[Assert\Callback]
    public function validateDates(ExecutionContextInterface $context): void
    {
        if ($this->dateHeureDebut && $this->dateLimiteInscription) {
            if ($this->dateHeureDebut <= $this->dateLimiteInscription) {
                $context->buildViolation('La date de début de la sortie doit être ultérieure à celle de la date de limite d\'inscription')
                    ->atPath('stop')
                    ->addViolation();
            }
        }
    }

    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?User $organisateur): static
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function isParticipant(User $user): bool
    {
        foreach ($this->participants as $participant) {
            if ($participant->getId() === $user->getId()) {
                return true;
            }
        }
        return false;
    }

}
