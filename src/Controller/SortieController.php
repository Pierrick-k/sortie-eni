<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Form\FiltreSortieType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sortie', name: 'sortie')]
final class SortieController extends AbstractController
{
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
    public function create(EntityManagerInterface $em, Request $request, EtatRepository $etatRepository, UserRepository $userRepository): Response{
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $action = $request->get('action');
            if($action === "Enregistrer"){
                $etat = $etatRepository->findOneBy(['libelle' => 'En création']);
                $sortie->setEtat($etat);
            }
            if($action === "Publier"){
                $etat = $etatRepository->findOneBy(['libelle' => 'Ouverte']);
                $sortie->setEtat($etat);
            }
            //TODO: A enlever une fois connexion mis en place
            $user = $userRepository->findOneBy(['email'=>'admin@eni.fr']);
            $sortie->setOrganisateur($user);
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
            $action = $request->get('action');
            if($action === "Enregistrer"){
                $etat = $etatRepository->findOneBy(['libelle' => 'En création']);
                $sortie->setEtat($etat);
            }
            if($action === "Publier"){
                $etat = $etatRepository->findOneBy(['libelle' => 'Ouverte']);
                $sortie->setEtat($etat);
            }
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'La sortie à bien été modifiée !');
            return $this->redirectToRoute('sortie_detail', ['id'=> $sortie->getId()]);
        }
        return $this->render('sortie/create.html.twig',[
            'sortieForm' => $sortieForm,
        ]);
    }



    #[Route('/list', name:'_list', methods: ['GET'])]
    public function list(SortieRepository $sortieRepository): Response{
        $sorties = $sortieRepository->findAll();

        $FiltreSortie = $this->createForm(FiltreSortieType::class);

        return $this->render('sortie/list.html.twig', [
            'sorties' => $sorties,
            'FiltreSortie' => $FiltreSortie,
        ]);
    }
}
