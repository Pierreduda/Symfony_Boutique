<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(30, "categorie", function ($num) {
            $categorie = new Categorie;
            $categorie->setTitre("Categorie_$num");
            $categorie->setMotsCles($this->faker->colorName);
            return $categorie;
        });
        $manager->flush();
    }
}
