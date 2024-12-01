<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixture extends Fixture
{
    private array $categories = ['Front-end', 'Back-end', 'CMS', 'Dev application', 'gestion base de données'];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->categories as $category) {
            $cat = new Category();
            $faker = Factory::create();
            $cat->setName($category);
            $cat->setDescription($faker->sentence(10));
            $manager->persist($cat);
        }
        $manager->flush();
    }
}
