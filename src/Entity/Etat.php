<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EtatRepository::class)]
class Etat
{
    public const EN_CREATION = 'En création';
    public const OUVERTE = 'Ouverte';
    public const CLOTUREE = 'Clôturée';
    public const EN_COURS = 'En cours';
    public const TERMINEE = 'Terminée';
    public const ANNULEE = 'Annulée';
    public const HISTORISEE = 'Historisée';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['etatSorties'])]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Sortie>
     */
    #[ORM\OneToMany(targetEntity: Sortie::class, mappedBy: 'etat')]
    private Collection $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function etat(string $libelle)
    {
        $this->sorties = new ArrayCollection();
        $this->libelle = $libelle;
    }

    public static function checkEtat(?string $etat, ?int $exclude)
    {
        $valid = false;
        switch ($etat) {
            case self::EN_CREATION:
            case self::TERMINEE:
                $valid = false;

                if ($exclude != 1) {
                    $valid = true;
                }
                break;
            case self::OUVERTE:
            case self::CLOTUREE:
            case self::HISTORISEE:
            case self::EN_COURS:
            case self::ANNULEE:
                $valid = true;

                break;
        }
        return $valid;
    }

    public static function getEtat(mixed $etat): Etat
    {
        $convertEtat = null;
        switch ($etat) {
            case self::EN_CREATION:
                $convertEtat = new Etat(self::EN_CREATION);
                break;
            case self::TERMINEE:
                $convertEtat = new Etat(self::TERMINEE);
                break;
            case self::OUVERTE:
                $convertEtat = new Etat(self::OUVERTE);
                break;
            case self::CLOTUREE:
                $convertEtat = new Etat(self::CLOTUREE);
                break;
            case self::HISTORISEE:
                $convertEtat = new Etat(self::HISTORISEE);
                break;
            case self::EN_COURS:
                $convertEtat = new Etat(self::EN_COURS);
                break;
            case self::ANNULEE:
                $convertEtat = new Etat(self::ANNULEE);
                break;
        }

        return $convertEtat;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): static
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties->add($sorty);
            $sorty->setEtat($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): static
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getEtat() === $this) {
                $sorty->setEtat(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->libelle;
    }


}
