<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
#[Route('/admin', name: 'admin')]
final class AdminController extends AbstractController
{
    #[Route('', name: '_home',methods:['GET'])]
    public function home(): Response
    {
        return $this->render('admin/home.html.twig');
    }

    #[Route('/campus', name: '_campus',methods:['GET'])]
    public function campus(): Response
    {
        return $this->render('admin/campus.html.twig');
    }

    #[Route('/cities', name: '_cities',methods:['GET'])]
    public function cities(): Response
    {
        return $this->render('admin/cities.html.twig');
    }

    #[Route('/users', name: '_users',methods:['GET'])]
    public function suers(): Response
    {
        return $this->render('admin/users.html.twig');
    }

}
