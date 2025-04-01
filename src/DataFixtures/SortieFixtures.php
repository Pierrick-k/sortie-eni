<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [LieuFixtures::class,CampusFixtures::class,EtatFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $faker= \Faker\Factory::create('fr_FR');
        for($i=0;$i<15;$i++){
            $sortie= new Sortie();
            $sortie->setNom($faker->sentence(10));
            $sortie->setDuree($faker->numberBetween(1, 50));

            $sortie->setLieu($this->getReference('lieu'.$faker->numberBetween(0,9),Lieu::class));
            $sortie->setCampus($this->getReference('campus'.$faker->numberBetween(1,5),Campus::class));
            $dateDebut = $faker->dateTimeBetween('+1 day', '+30 days');
            $sortie->setDateHeureDebut(\DateTimeImmutable::createFromMutable($dateDebut));
            $sortie->setNbInscriptionMax($faker->numberBetween(5,20));
            $sortie->setEtat($this->getReference('etat'.$faker->numberBetween(1,7),Etat::class));
            $sortie->setDateLimiteInscription(\DateTimeImmutable::createFromMutable($dateDebut));
            $sortie->setInfosSortie($faker->sentence(10));
            $manager->persist($sortie);
        }
        $manager->flush();
    }
}
