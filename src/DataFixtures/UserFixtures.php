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
        $faker= \Faker\Factory::create('fr_FR');

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


        for($i=0; $i<15; $i++) {
            $user = new User();
            $prenom = $faker->firstName();
            $user->setPrenom($prenom);
            $user->setNom($faker->lastName());
            $user->setTelephone($faker->phoneNumber());
            $user->setEmail($faker->email());
            $user->setPassword($this->passwordHasher->hashPassword($user, $prenom));
            $user->setAdministrateur(false);
            $user->setRoles(['ROLE_USER']);
            $user->setActif(true);
            $manager->persist($user);
            $this->addReference( 'user' . $i, $user);
        }
        $manager->flush();
   }
}
