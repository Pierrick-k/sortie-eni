<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $etat1 = new Etat();
        $etat1->setLibelle('En création');
        $this->addReference('etat1', $etat1);
        $etat2 = new Etat();
        $etat2->setLibelle('Ouverte');
        $this->addReference('etat2', $etat2);
        $etat3 = new Etat();
        $etat3->setLibelle('Clôturée');
        $this->addReference('etat3', $etat3);
        $etat4 = new Etat();
        $etat4->setLibelle('En cours');
        $this->addReference('etat4', $etat4);
        $etat5 = new Etat();
        $etat5->setLibelle('Terminée');
        $this->addReference('etat5', $etat5);
        $etat6 = new Etat();
        $etat6->setLibelle('Annulée');
        $this->addReference('etat6', $etat6);
        $etat7 = new Etat();
        $etat7->setLibelle('Historisée');
        $this->addReference('etat7', $etat7);
        $manager->persist($etat1);
        $manager->persist($etat2);
        $manager->persist($etat3);
        $manager->persist($etat4);
        $manager->persist($etat5);
        $manager->persist($etat6);
        $manager->persist($etat7);

        $manager->flush();
    }
}
