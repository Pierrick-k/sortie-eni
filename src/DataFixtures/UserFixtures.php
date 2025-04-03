<?php

namespace App\DataFixtures;

use App\Entity\Campus;
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
        $campus = $manager->getRepository(Campus::class)->findAll();

        $admin = new User();
        $admin->setPrenom('Admin');
        $admin->setNom('Admin');
        $admin->setTelephone('0123456789');
        $admin->setEmail('admin@eni.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setAdministrateur(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setActif(false);
        $admin->setCampus($faker->randomElement($campus));

        $manager->persist($admin);


        for($i=0; $i<15; $i++) {
            $user = new User();
            $prenom = $faker->firstName();
            $user->setPrenom($prenom);
            $user->setNom($faker->lastName());
            $user->setTelephone('0123456789');
            $user->setEmail($faker->email());
            $user->setPassword($this->passwordHasher->hashPassword($user, $prenom));
            $user->setAdministrateur(false);
            $user->setRoles(['ROLE_USER']);
            $user->setActif(true);
            $user->setCampus($faker->randomElement($campus));
            $manager->persist($user);
            $this->addReference( 'user' . $i, $user);
        }
        $manager->flush();
   }
}
