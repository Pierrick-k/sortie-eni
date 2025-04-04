<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Form\SearchFormType;
use App\Repository\CampusRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/campus',name:'campus')]
final class CampusController extends AbstractController
{
    #[Route('/list', name: '_list',methods:['GET','POST'])]
    public function list(CampusRepository $campusRepository, Request $request, EntityManagerInterface $em): Response
    {
        $nom = $request->query->get('nom');
        if ($nom) {
            $campus = $campusRepository->findByName($nom);
        } else {
            $campus = $campusRepository->findAll();
        }

        $nouvCampus = new Campus();
        $searchForm = $this->createForm(SearchFormType::class);
        $searchForm->handleRequest($request);

        $campusForm = $this->createForm(CampusType::class, $nouvCampus);
        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $em->persist($nouvCampus);
            $em->flush();
            $this->addFlash('success', "Le campus a bien été ajouté");
            return $this->redirectToRoute('campus_list');
        }
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            return $this->redirectToRoute('campus_list', [
                'nom' => $data['nom'],
            ]);
        }

        return $this->render('admin/campus.html.twig', [
            'campus' => $campus,
            'campusForm' => $campusForm,
            'searchForm' => $searchForm,
        ]);
    }

    #[Route('/update/{id}', name: '_update', requirements: ['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function update(Campus $campus, Request $request, EntityManagerInterface $em): Response
    {
        $campusForm = $this->createForm(CampusType::class, $campus);
        $campusForm->handleRequest($request);
        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $em->persist($campus);
            $em->flush();
            $this->addFlash('success','Le campus a bien été modifié');
            return $this->redirectToRoute('campus_list');
        }
        return $this->render('campus/edit.html.twig',[
            'campusForm' => $campusForm,
        ]);
    }

    #[Route('delete/{id}', name: '_delete',requirements:["id"=>"\d+"],methods:['GET','POST'])]
    public function deleteCity(Campus $campus, EntityManagerInterface $em): Response
    {
        try {
            $em->remove($campus);
            $em->flush();
            $this->addFlash('success', "Le campus a bien été supprimmé");
            return $this->redirectToRoute('campus_list');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('danger', "Impossible de supprimer ce campus : contrainte de clé étrangère non respectée.");
        }
        return $this->redirectToRoute('campus_list');
    }
}
