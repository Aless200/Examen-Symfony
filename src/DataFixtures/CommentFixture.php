<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Course;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixture extends Fixture implements DependentFixtureInterface
{
    private array $users;
    private array $courses;

    public function load(ObjectManager $manager): void
    {
        $this->users = $manager->getRepository(User::class)->findAll();
        $this->courses = $manager->getRepository(Course::class)->findAll();
        $faker = Factory::create();
        foreach ($this->courses as $course) {
            for ($i = 0; $i < $faker->numberBetween(1, 10); $i++) {
                $cmt = new Comment();
                $cmt->setUser($faker->randomElement($this->users))
                    ->setCourse($course)
                    ->setContent($faker->realText($faker->numberBetween(10, 50)))
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setPublished($faker->boolean(70))
                    ->setSend(false)
                    ->setAssement($faker->numberBetween(1,5))
                ;
                $manager->persist($cmt);
            }

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CourseFixture::class
        ];
    }
}
