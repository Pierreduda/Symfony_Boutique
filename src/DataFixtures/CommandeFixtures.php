<?php

namespace App\DataFixtures;

use App\Entity\Commande;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommandeFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(40, "commande", function ($num) {
            $commande = new Commande;
            $commande->setMontant($this->faker->randomFloat(2, 100, 5000));
            $commande->setDateEnregistrement($this->faker->dateTime("now"));
            $commande->setEtat($this->faker->randomElement(["en cours", "en attente", "livrée"]));
            $commande->setMembre($this->getRandomReference("membre"));
            return $commande;
        });
        $manager->flush();
    }

    public function getDependencies()
    {
        return [MembreFixtures::class];
    }
}
