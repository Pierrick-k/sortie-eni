<?php

namespace App\Util;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Repository\EtatRepository;

class UpdateEtat
{
    public function __construct(private EtatRepository $etatRepository){

    }

    public function updateEtat( string $action, Sortie $sortie ):bool
    {
        if($action === "Enregistrer"){
            $etat = $this->etatRepository->findOneBy(['libelle' => etat::EN_CREATION]);
            $sortie->setEtat($etat);
            return true;
        }
        if($action === "Publier"){
            $etat = $this->etatRepository->findOneBy(['libelle' => etat::OUVERTE]);
            $sortie->setEtat($etat);
            return true;
        }
        if($action === "Annulation"){
            $etat = $this->etatRepository->findOneBy(['libelle' => etat::ANNULEE]);
            $sortie->setEtat($etat);
            return true;
        }
        return false;
    }

}