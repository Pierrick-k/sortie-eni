<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\Model\FiltreSortieModel;
use App\Form\SortieType;
use App\Form\FiltreSortieType;
use App\Repository\SortieRepository;
use App\Util\UpdateEtat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/sortie', name: 'sortie')]
final class SortieController extends AbstractController
{
    #[Route('/detail/{id}', name:'_detail', methods: ['GET'])]
    public function detail($id, SortieRepository $sortieRepository): Response{
        $sortie = $sortieRepository->findSortieById($id);
        if(!$sortie){
            //TODO: renvoyer sur une page d'erreur 404
            return $this->redirectToRoute('sortie_list');
        }

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/create', name:'_create',methods:['GET','POST'])]
    public function create(EntityManagerInterface $em, Request $request, UpdateEtat $updateEtat): Response{
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $action = $request->get('action');
            $updateEtat->updateEtat($action, $sortie);
            $sortie->setOrganisateur($this->getUser());
            $sortie->setCampus($this->getUser()->getCampus());
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
    public function list(SortieRepository $sortieRepository, EntityManagerInterface $em, Request $request): Response{
        $filtreSortieModel = new FiltreSortieModel();
        $FiltreSortie = $this->createForm(FiltreSortieType::class, $filtreSortieModel);
        $FiltreSortie->handleRequest($request);

        if( $FiltreSortie->isSubmitted()) :
            $sorties = $sortieRepository->findSortieByFilter($filtreSortieModel, $this->getUser());
        else:
            $sorties = $sortieRepository->findAll();
        endif;

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

    #[Route('/desinscription/{id}', name:'_desinscription', methods: ['DELETE', 'GET'])]
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

    #[Route('/cancel/{id}', name:'_annulation', requirements: ["id" => "\d+"], methods: ['GET', 'POST'])]
    public  function cancel(Sortie $sortie, EntityManagerInterface $em, Request $request, UpdateEtat $updateEtat): Response{
        $user = $this->getUser();
        if ($sortie->getOrganisateur() == $user || $this->isGranted('ROLE_ADMIN')) {
            if ($updateEtat->updateEtat('Annulation', $sortie)) {
                $sortie->appendInfosSortie($request->get('motif-annulation'));
                $em->persist($sortie);
                $em->flush();
                $this->addFlash('success', 'La sortie à bien été Annulée !');

                return $this->redirectToRoute('sortie_list', ['id'=> $sortie->getId()]);
            } else {
                $this->addFlash('danger', 'Action impossible');
            }
        } else {
            $this->addFlash('warning', 'Annulation impossible');
        }
        return $this->redirectToRoute('sortie_list', ['id' => $sortie->getId()]);

    }
}
