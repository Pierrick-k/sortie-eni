<?php

namespace App\Util;

use App\Entity\Sortie;
use App\Repository\EtatRepository;

class UpdateEtat
{
    public function __construct(private EtatRepository $etatRepository){

    }
    public function updateEtat(string $action, Sortie $sortie){
        if($action === "Enregistrer"){
            $etat = $this->etatRepository->findOneBy(['libelle' => 'En création']);
            $sortie->setEtat($etat);
        }
        if($action === "Publier"){
            $etat = $this->etatRepository->findOneBy(['libelle' => 'Ouverte']);
            $sortie->setEtat($etat);
        }
    }

}