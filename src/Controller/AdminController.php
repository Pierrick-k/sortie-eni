<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Ville;
use App\Form\CampusType;
use App\Form\SearchFormType;
use App\Form\VilleType;
use App\Repository\CampusRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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



}
