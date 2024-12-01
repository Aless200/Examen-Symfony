<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class NewsFixtures extends Fixture
{
    public function __construct(private readonly sluggerInterface $slugger)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 8; $i++) {
            $news = new News();
            $newsName = $faker->words(2, true);
            $news
                ->setImage($i . '.jpg')
                ->setName($newsName)
                ->setContent($faker->paragraph(2))
                ->setCreatedAt(new \DateTimeImmutable());
            $news->setUpdatedAt(new \DateTimeImmutable());
            $news->setPublished($faker->boolean(90))
                ->setSlug($this->slugger->slug($news->getName()));
            $manager->persist($news);
        }
        $manager->flush();
    }
}
