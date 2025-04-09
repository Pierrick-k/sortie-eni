<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/lieu', name: 'api_')]
final class LieuController extends AbstractController
{
    #[Route('/{id}', name: 'lieu', methods: ['GET'])]
    public function getLieu(int $id, LieuRepository $lieuRepository, SerializerInterface $serializer): JsonResponse
    {
        $lieu = $lieuRepository->find($id);

        if (!$lieu) {
            return $this->json(['error' => 'Lieu non trouvé'], 404);
        }
        $result = $serializer->serialize($lieu,'json',["groups"=>'getInfosLieu']);
        return new JsonResponse($result,Response::HTTP_OK,[],true);
    }
    #[Route('/par-ville/{id}', name: 'lieu_par_ville', methods: ['GET'])]
    public function getLieuxByVille(Ville $ville, LieuRepository $lieuRepository): JsonResponse
    {
        $lieux = $lieuRepository->findBy(['ville' => $ville]);

        $data = [];
        foreach ($lieux as $lieu) {
            $data[] = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom(),
            ];
        }

        return $this->json($data);
    }

}
