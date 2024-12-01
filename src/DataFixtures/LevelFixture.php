<?php

namespace App\DataFixtures;

use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LevelFixture extends Fixture
{
    private array $level = ['Débutant', 'Intermédiaire', 'Avancé'];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->level as $level) {
            $newLevel = new Level();
            $faker = Factory::create();
            $newLevel->setName($level);
            $newLevel->setPrerequisite($faker->sentence(10));
            $manager->persist($newLevel);
        }
        $manager->flush();
    }
}
