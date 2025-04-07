<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\SearchFormType;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('/ville', name: 'ville')]
final class VilleController extends AbstractController
{
    #[Route('/list', name: '_list', methods:['GET', 'POST'])]
    public function list(VilleRepository $villeRepository, Request $request, EntityManagerInterface $em): Response
    {
        $nom = $request->query->get('nom');

        if ($nom) {
            $cities = $villeRepository->findByName($nom);
        } else {
            $cities = $villeRepository->findAll();
        }

        $city = new Ville();
        $searchForm = $this->createForm(SearchFormType::class);
        $searchForm->handleRequest($request);

        $villeForm = $this->createForm(VilleType::class, $city);
        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $em->persist($city);
            $em->flush();
            $this->addFlash('success', "La ville a bien été ajoutée");
            return $this->redirectToRoute('ville_list');
        }
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            return $this->redirectToRoute('ville_list', [
                'nom' => $data['nom'],
            ]);
        }

        return $this->render('admin/cities.html.twig', [
            'cities' => $cities,
            'villeForm' => $villeForm,
            'searchForm' => $searchForm,
        ]);
    }


    #[Route('/update/{id}', name: '_update', requirements: ['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function update(Ville $ville, Request $request, EntityManagerInterface $em): Response
    {
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);
        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $em->persist($ville);
            $em->flush();
            $this->addFlash('success','La ville a bien été modifiée');
            return $this->redirectToRoute('ville_list');
        }
        return $this->render('ville/edit.html.twig',[
            'villeForm' => $villeForm,
        ]);
    }

    #[Route('delete/{id}', name: '_delete',requirements:["id"=>"\d+"],methods:['GET','POST'])]
    public function deleteCity(Ville $ville, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        try {
            $em->remove($ville);
            $em->flush();
            $this->addFlash('success', "La ville a bien été supprimmée");
            return $this->redirectToRoute('ville_list');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('danger', "Impossible de supprimer cette ville : contrainte de clé étrangère non respectée.");
        }
        return $this->redirectToRoute('ville_list');
    }
}
