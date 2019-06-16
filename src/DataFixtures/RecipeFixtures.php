<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RecipeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 100; $i++)
        {
            $recipe = new Recipe();
            $recipe
                ->setName($faker->words(3, true))
                ->setCategory($faker->randomElement(['Vegan','Glutenfree','Lactosefree', 'Vegetarian']))
                ->setCookingTime($faker->numberBetween(0, 300))
                ->setCost($faker->numberBetween(1, 3))
                ->setDifficulty($faker->numberBetween(1, 3))
                ->setFoodType($faker->randomElement(['Française','Espagnole','Chinoise', 'Italienne']))
                ->setNumberPersons($faker->numberBetween(1, 8))
                ->setPreparationTime($faker->numberBetween(0, 300))
                ->setTotalTime($faker->numberBetween(0, 600))
                ->setType($faker->randomElement(['Entrée','Plat','Dessert', 'Apéritif']));
            $manager->persist($recipe);
        }
        $manager->flush();
    }
}
