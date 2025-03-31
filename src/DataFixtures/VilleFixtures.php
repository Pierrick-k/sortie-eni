<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ville1 = new Ville();
        $ville1->setNom('Rennes');
        $ville1->setCodePostal('35000');
        $this->addReference('ville1', $ville1);
        $manager->persist($ville1);
        $ville2 = new Ville();
        $ville2->setNom('Nantes');
        $ville2->setCodePostal('44109');
        $this->addReference('ville2', $ville2);
        $manager->persist($ville2);
        $ville3 = new Ville();
        $ville3->setNom('Quimper');
        $ville3->setCodePostal('29000');
        $this->addReference('ville3', $ville3);
        $manager->persist($ville3);
        $manager->flush();

    }
}
