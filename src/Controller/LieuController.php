<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class LieuController extends AbstractController
{/*
    public function __construct(private readonly LieuService $lieuService)
    {
    }*/

    #[Route('/api/lieu/{id}', name: 'api_lieu', methods: ['GET'])]
    public function getLieu(int $id, LieuRepository $lieuRepository, SerializerInterface $serializer): JsonResponse
    {
        $lieu = $lieuRepository->find($id);

        if (!$lieu) {
            return $this->json(['error' => 'Lieu non trouvé'], 404);
        }
        $result = $serializer->serialize($lieu,'json',["groups"=>'getInfosLieu']);
        return new JsonResponse($result,Response::HTTP_OK,[],true);
    }
}
