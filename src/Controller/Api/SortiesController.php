<?php

namespace App\Controller\Api;

use App\Entity\Etat;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateTimeInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as ApiRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class SortiesController extends AbstractController
{
    const DEBUT_PLAGE = 1;
    const FIN_PLAGE = 2;
    const INTACTE = 3;

    #[Route('/api/sorties', name: 'api_sorties_list', methods: ["GET"])]
    public function index(
        ApiRequest $request,
        SortieRepository $sortieRepository,
        EtatRepository $etatRepository,
        SerializerInterface $serializer): Response
    {
        $body = $request->getContent();
        $param = json_decode($body, true);

        $etat = null;
        if (!empty($param['etat'])) {
            if(etat::checkEtat($param['etat'], 1)) {
                $etat = $etatRepository->findOneBy(["libelle"=>$param['etat']]);
            }
        }

        $dateDebut = null;
        if (!empty($param['dateDebut'])) {
           $dateDebut = self::formatDate($param['dateDebut'], self::DEBUT_PLAGE);
        }

        $dateFin = null;
        if (!empty($param['dateFin'])) {
            $dateFin = self::formatDate($param['dateFin'], self::FIN_PLAGE);
        } else {
            if ($dateDebut !== null) {
                $dateDebut = self::formatDate($param['dateDebut'], self::INTACTE);;
            }
        }

        $groups = ["groups" => ['baseSorties', 'etatSorties', 'lieuSorties', 'campusSorties', 'participantsSorties']];
        if (!empty($param['sortie'])) {
            if($param['sortie'] == 'minimal') {
                $groups = ["groups" => ['minimalSorties', 'etatSorties']];
            }
        }

        $sorties = $sortieRepository->findSortieByAPIFilter($etat, $dateDebut, $dateFin);

        return $this->json($sorties, Response::HTTP_OK, [],  $groups);
    }

    private static function formatDate(string $myDate, ?int $position): \DateTimeImmutable
    {
        // 2004-02-12T15:19:21+00:00
        $dateFormatee = \DateTimeImmutable::createFromFormat(DateTimeInterface::ISO8601, $myDate);
        if (!$dateFormatee) {
            // 2025-04-18 09:26:00
            $dateFormatee = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $myDate);
        }
        if (!$dateFormatee) {
            // 2025-04-18 09:26
            $dateFormatee = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $myDate);
        }
        if (!$dateFormatee) {
            // 2025-04-18
            $dateFormatee = \DateTimeImmutable::createFromFormat('Y-m-d', $myDate);
        }

        if ( $position == 2 ) {
            $date = new \DateTimeImmutable($dateFormatee->format('Y-m-d 23:59:59'));
        } elseif($position == 1 ) {
            $date = new \DateTimeImmutable($dateFormatee->format('Y-m-d 00:00:00'));
        } else {
            $date = new \DateTimeImmutable($dateFormatee->format('Y-m-d H:i:s'));
        }

        return $date;
    }

}
