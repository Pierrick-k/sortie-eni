<?php

namespace App\Controller\Api;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class SortiesController extends AbstractController
{
    #[Route('/api/sorties', name: 'api_sorties_list', methods: ["GET"])]
    public function index(SortieRepository $sortieRepository, SerializerInterface $serializer): Response
    {
        /*

         Une API doit permettre d’extraire la liste des sorties et être mise à
disposition pour des applications tierces.
Possibilité de filtrer les sorties selon l’état et la date prévue. On ne
pourra pas extraire les sorties qui sont en cours de création ou
terminées.
3
Gestion d’une API
Liste des sorties
L’API ne sera accessible uniquement pour des utilisateurs
authentifiés.

         */



        $sorties = $sortieRepository->findBy([],["nom" => "ASC"]);

        return $this->json($sorties, Response::HTTP_OK, [],  ["groups" => ['baseSorties', 'etatSorties', 'lieuSorties', 'campusSorties', 'participantsSorties']]);

    }
}
