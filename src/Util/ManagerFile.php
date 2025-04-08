<?php

namespace App\Util;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class ManagerFile
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function upload($fichier,?string $ancienFichier,User $user){
        if($user->getFichier()){
            $this->delete($user->getFichier(), $ancienFichier);
        }
        $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $fichier->guessExtension();
        try {
            $fichier->move($ancienFichier, $newFilename);
            $user->setFichier($newFilename);
        } catch (FileException $e) {
            dd($e->getMessage());
        }
    }

    public function delete($file, $fichierDirectory){
        $filePath = $fichierDirectory . '/' . $file;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

    }
}