<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(15, "categorie", function ($num) {
            $categorie = new Categorie;
            $categorie->setTitre($this->faker->word);
            $categorie->setMotsCles($this->faker->words($this->faker->randomDigit, true));
            $categorie->addProduit($this->getRandomReference("produit"));
            $categorie->addProduit($this->getRandomReference("produit"));
            return $categorie;
        });
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProduitFixtures::class];
    }
}
