<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Util\ManagerFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/user', name: 'user_')]
final class UserController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
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
    public function update($id, UserRepository $userRepository,
                           EntityManagerInterface $em,
                           Request $request,
                           UserPasswordHasherInterface $passwordHasher,
                           #[Autowire('%kernel.project_dir%/public/uploads/images/user')] string $fichierDirectory,
                           ManagerFile $managerFile): Response
    {
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        if (!$user instanceof User) {
            throw new \LogicException('Erreur: L\'utilsateur n\'est pas une instance de: User.');
        }
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $newPassword = $userForm->get('newPassword')->getData();
            if ($newPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }
            if($userForm->has('deleteImg')){
                if ($userForm->get('deleteImg')->getData()) {
                    if ($user->getFichier()) {
                        $managerFile->delete($user->getFichier(), $fichierDirectory);
                    }
                    $user->setFichier(null);
                }
            }
            $fichier = $userForm->get('fichier')->getData();
            if($fichier){
                $managerFile->upload($fichier, $fichierDirectory, $user);
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profile updated successfully.');
            return $this->redirectToRoute('user_detail', ['id' => $user->getId()]);
        }
        if($this->getUser()->getId() != $user->getId() && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
            return $this->render('user/detail.html.twig', ["user" => $this->getUser()]);
        }
        return $this->render('user/update.html.twig', [
            'userForm' => $userForm,
            'user'=>$user,
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail($id, UserRepository $userRepository): Response{
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        if($this->getUser()->getId() != $user->getId() && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
            return $this->render('user/detail.html.twig', ["user" => $this->getUser()]);
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
    public function updateProfile( EntityManagerInterface $em,
                                   Request $request,
                                   UserPasswordHasherInterface $passwordHasher,
                                   #[Autowire('%kernel.project_dir%/public/uploads/images/user')] string $fichierDirectory,
                                   SluggerInterface $slugger,
                                   ManagerFile $managerFile): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException();
        }
        if (!$user instanceof User) {
            throw new \LogicException('L\'user n\'est pas un instance de: App\Entity\User.');
        }
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $newPassword = $userForm->get('newPassword')->getData();
            if ($newPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }
            if($userForm->has('deleteImg')){
                if ($userForm->get('deleteImg')->getData()) {
                    if ($user->getFichier()) {
                        $managerFile->delete($user->getFichier(), $fichierDirectory);
                    }
                    $user->setFichier(null);
                }
            }
            $fichier = $userForm->get('fichier')->getData();
            if($fichier){
                $managerFile->upload($fichier, $fichierDirectory, $user);
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profile updated successfully.');
            return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
        }
        return $this->render('user/update.html.twig', [
            'userForm' => $userForm,
            'user'=>$user,
        ]);
    }
}
