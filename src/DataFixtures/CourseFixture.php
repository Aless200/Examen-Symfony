<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class CourseFixture extends Fixture implements DependentFixtureInterface
{

    public function __construct(private readonly sluggerInterface $slugger)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $duration = ['2 mois', '4 mois', '6 mois', '8 mois', '10 mois', '1 an', '2 ans'];

        $faker = Factory::create();
        $category = $manager->getRepository(Category::class)->findAll();
        $level = $manager->getRepository(Level::class)->findAll();
        for ($i = 0; $i < 15; $i++) {
            $course = new Course();
            $courseName = $faker->words(2, true);
            $course->setName($courseName)
                    ->setSmallDescription($faker->paragraphs(3, true))
                    ->setFullDescription($faker->paragraphs(10, true))
                    ->setDuration($faker->randomElement($duration))
                    ->setCategory($faker->randomElement($category))
                    ->setLevel($faker->randomElement($level))
                    ->setPublished($faker->boolean(90))
                    ->setPrice($faker->randomFloat(2, 100, 900))
                    ->setSlug($this->slugger->slug($course->getName()))
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days', 'now')))
                    ->setimage($i .'.jpg')
                    ->setProgram("program.pdf");
            $manager->persist($course);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixture::class,
            LevelFixture::class,
        ];
    }
}
