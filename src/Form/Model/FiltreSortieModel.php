<?php

namespace App\Form\Model;

use App\Entity\Campus;

class FiltreSortieModel
{
    public Campus $campus;

    /** @var bool  */
    public bool $mesSorties;

    /** @var bool  */
    public bool $sortiesInscrit;

    /** @var bool  */
    public bool $sortiesPasInscrit;

    /** @var bool  */
    public bool $sortiesTerminees;

    /** @var string  */
    public string $nom;

    /** @var \DateTimeImmutable|null  */
    public ?\DateTimeImmutable  $dateDebut;

    /** @var \DateTimeImmutable|null  */
    public ?\DateTimeImmutable  $dateFin;
}