<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $campus1= new Campus();
        $campus1->setNom('Campus de Nantes');
        $this->addReference('campus1', $campus1);
        $campus2= new Campus();
        $campus2->setNom('Campus de Rennes');
        $this->addReference('campus2', $campus2);
        $campus3= new Campus();
        $campus3->setNom('Campus de Quimper');
        $this->addReference('campus3', $campus3);
        $campus4= new Campus();
        $campus4->setNom('Campus de Niort');
        $this->addReference('campus4', $campus4);
        $campus5= new Campus();
        $campus5->setNom('Campus en ligne');
        $this->addReference('campus5', $campus5);
        $manager->persist($campus1);
        $manager->persist($campus2);
        $manager->persist($campus3);
        $manager->persist($campus4);
        $manager->persist($campus5);


        $manager->flush();
    }
}
