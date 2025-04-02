<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sortie', name: 'sortie')]
final class SortieController extends AbstractController
{
    #[Route('/liste', name: '_liste', methods: ['GET'])]
    public function liste(): Response
    {
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    #[Route('/detail/{id}', name:'_detail', methods: ['GET'])]
    public function detail($id, SortieRepository $sortieRepository): Response{
        $sortie = $sortieRepository->findSortieById($id);
        if(!$sortie){
            //TODO: renvoyer sur une page d'erreur 404
            return $this->redirectToRoute('sortie_liste');
        }

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/create', name:'_create',methods:['GET','POST'])]
    public function create(EntityManagerInterface $em, Request $request, EtatRepository $etatRepository): Response{
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $etat = $etatRepository->findOneBy(['libelle' => 'En création']);
            $sortie->setEtat($etat);
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'La sortie à bien été créée !');
            return $this->redirectToRoute('sortie_detail', ['id'=> $sortie->getId()]);
        }
        return $this->render('sortie/create.html.twig',[
            'sortieForm' => $sortieForm,
        ]);
    }

    #[Route('/update/{id}', name:'_update',methods:['GET','POST'])]
    public function update(Sortie $sortie, EntityManagerInterface $em, Request $request, EtatRepository $etatRepository): Response{
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'La sortie à bien été modifiée !');
            return $this->redirectToRoute('sortie_detail', ['id'=> $sortie->getId()]);
        }
        return $this->render('sortie/create.html.twig',[
            'sortieForm' => $sortieForm,
        ]);
    }


}
