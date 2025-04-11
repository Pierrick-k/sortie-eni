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

    public static function checkEtat(?Etat $etat, ?int $exclude)
    {
        $valid = false;
        switch ($etat) {
            case Etat::EN_CREATION:
            case Etat::TERMINEE:
                $valid = false;

                if ($exclude != 1) {
                    $valid = true;
                }
                break;
            case Etat::OUVERTE:
            case Etat::CLOTUREE:
            case Etat::HISTORISEE:
            case Etat::EN_COURS:
            case Etat::ANNULEE:
                $valid = true;

                break;
        }
        return $valid;
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

}
