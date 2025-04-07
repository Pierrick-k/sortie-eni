<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\SortieType;
use App\Form\FiltreSortieType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Util\UpdateEtat;
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
    public function create(EntityManagerInterface $em, Request $request, UserRepository $userRepository, UpdateEtat $updateEtat): Response{
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $action = $request->get('action');
            $updateEtat->updateEtat($action, $sortie);
            //TODO: A enlever une fois connexion mise en place
            $user = $userRepository->findOneBy(['email'=>'admin@eni.com']);
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

    #[Route('/update/{id}', name:'_update', requirements: ["id"=>"\d+"],methods:['GET','POST'])]
    public function update(Sortie $sortie, EntityManagerInterface $em, Request $request, UpdateEtat $updateEtat): Response{
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $action = $request->get('action');
            $updateEtat->updateEtat($action, $sortie);
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'La sortie à bien été modifiée !');
            return $this->redirectToRoute('sortie_detail', ['id'=> $sortie->getId()]);
        }
        return $this->render('sortie/edit.html.twig',[
            'sortieForm' => $sortieForm,
        ]);
    }

    #[Route('/delete/{id}', name:'_delete', requirements: ["id"=>"\d+"], methods:['GET','POST'])]
    public function delete(Sortie $sortie, EntityManagerInterface $em): Response{
        $em->remove($sortie);
        $em->flush();
        $this->addFlash('success', 'La sortie à bien été supprimée !');
        return $this->redirectToRoute('sortie_list');
    }

    #[Route('/publish/{id}', name:'_publish', requirements: ["id"=>"\d+"],methods:['GET','POST'])]
    public function publish(Sortie $sortie, UpdateEtat $updateEtat, EntityManagerInterface $em):Response{
        if($sortie->getEtat()->getLibelle() === "En création"){
            $action = "Publier";
            $updateEtat->updateEtat($action, $sortie);
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', "La sortie à été publiée !");
            return $this->redirectToRoute('sortie_detail', ['id'=> $sortie->getId()]);
        }
        return $this->redirectToRoute('sortie_list');
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
    #[Route('/inscription/{id}', name:'_inscription', methods: ['GET','POST, PUT'])]
    public function inscription(Sortie $sortie, EntityManagerInterface $em): Response{
        $user = $this->getUser();
        if ($sortie->getParticipants()->contains($user)) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cette sortie.');
        } else {
            $sortie->addParticipant($user);
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Votre inscription à la sortie a été prise en compte.');
        }
        return $this->redirectToRoute('sortie_detail', ['id' => $sortie->getId()]);
    }
    #[Route('/desinscription/{id}', name:'_desinscription', methods: ['delete', 'GET'])]
    public  function desinscription(Sortie $sortie, EntityManagerInterface $em): Response{
        $user = $this->getUser();
        if ($sortie->getParticipants()->contains($user)) {
            $sortie->removeParticipant($user);
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Vous êtes desinscrit');
        } else {
            $this->addFlash('warning', 'Vous n\'êtes déjà pas inscrit à cette sortie');
        }
        return $this->redirectToRoute('sortie_detail', ['id' => $sortie->getId()]);

    }
}
