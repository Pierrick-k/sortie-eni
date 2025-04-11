<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
#[UniqueEntity(fields: ['nom'], message: 'Cette ville existe déjà')]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'le champ du nom doit être renseigné')]
    #[Assert\Length(min: 3,max: 255,
        minMessage:'Le nom de la ville doit faire minimum 3 caracteres' ,
        maxMessage: 'Le nom de la ville doit faire 255 caracteres maximum')]
    #[Groups(['lieuSorties'])]
    private ?string $nom = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank(message: 'le champ du code postal doit être renseigné')]
    #[Assert\Length(min:5,max:5, exactMessage: 'La valeur doit contenir exactement {{ limit }} caractères.')]
    #[Assert\Type(type: 'numeric', message: 'Le code postal doit être un nombre.')]
    #[Assert\GreaterThan(value: 10,
        message: 'Le code postal doit être supérieur à 0' )]
    #[Groups(['getInfosLieu','lieuSorties'])]
    private ?string $codePostal = null;

    /**
     * @var Collection<int, Lieu>
     */
    #[ORM\OneToMany(targetEntity: Lieu::class, mappedBy: 'ville', orphanRemoval: true)]
    private Collection $lieux;

    public function __construct()
    {
        $this->lieux = new ArrayCollection();
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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection<int, Lieu>
     */
    public function getLieux(): Collection
    {
        return $this->lieux;
    }

    public function addLieux(Lieu $lieux): static
    {
        if (!$this->lieux->contains($lieux)) {
            $this->lieux->add($lieux);
            $lieux->setVille($this);
        }

        return $this;
    }

    public function removeLieux(Lieu $lieux): static
    {
        if ($this->lieux->removeElement($lieux)) {
            // set the owning side to null (unless already changed)
            if ($lieux->getVille() === $this) {
                $lieux->setVille(null);
            }
        }

        return $this;
    }
}
