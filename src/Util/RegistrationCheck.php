<?php

namespace App\Util;

use App\Entity\Sortie;
use App\Entity\User;

class RegistrationCheck
{
    public function isUserInscrit(Sortie $sortie, User $user): bool
    {
        foreach ($sortie->getParticipants() as $participant) {
            if ($participant->getId() === $user->getId()) {
                return true;
            }
        }
        return false;
    }

}