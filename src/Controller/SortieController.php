<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sortie', name: 'sortie')]
final class SortieController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    #[Route('/detail/{id}', name:'_detail', methods: ['GET'])]
    public function detail($id, SortieRepository $sortieRepository): Response{
        $sortie = $sortieRepository->find($id);
        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);
    }
}
