<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setPrenom('Admin');
        $admin->setNom('Admin');
        $admin->setTelephone('0123456789');
        $admin->setEmail('admin@eni.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setAdministrateur(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setActif(false);
        $manager->persist($admin);
        $manager->flush();
   }
}
