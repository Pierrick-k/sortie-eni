<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Form\SearchFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/user', name: 'user_')]
final class UserController extends AbstractController
{
    #[Route('/list', name: 'list', methods: ['GET','POST'])]
    public function list(UserRepository $userRepository, Request $request, EntityManagerInterface $em): Response
    {
        $email = $request->query->get('nom');
        if ($email) {
            $users = $userRepository->findByEmail($email);
        } else {
            $users = $userRepository->findAll();
        }

        $searchForm = $this->createForm(SearchFormType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            return $this->redirectToRoute('user_list', [
                'nom' => $data['nom'],
            ]);
        }

        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'searchForm' => $searchForm,
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'] ,methods: ['GET', 'POST'])]
    public function update($id, UserRepository $userRepository, EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $newPassword = $userForm->get('newPassword')->getData();
            if ($newPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profile updated successfully.');
        }
        return $this->render('user/update.html.twig', [
            'userForm' => $userForm,
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
public function detail($id, UserRepository $userRepository): Response{
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        return $this->render('user/detail.html.twig', ["user" => $user]);
    }
    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function profile(): Response{
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException();
        }
        return $this->render('user/detail.html.twig', ["user" => $user]);
    }

    #[Route('/update/profile', name: 'update_profile', methods: ['GET', 'POST'])]
    public function updateProfile( EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException();
        }
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $newPassword = $userForm->get('newPassword')->getData();
            if ($newPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profile updated successfully.');
        }
        return $this->render('user/update.html.twig', [
            'userForm' => $userForm,
        ]);
    }


}
