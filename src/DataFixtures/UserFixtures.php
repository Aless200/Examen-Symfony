<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private object $hasher;

    private array $genders = ['male', 'female'];

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $gender = $faker->randomElement($this->genders);
            $user->setFirstName($faker->firstName($gender))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setRoles(['ROLE_USER'])
                ->setDisabled($faker->boolean(80));
            $gender = ($gender == 'male') ? 'm' : 'f';
            $user->setImage('0' . ($i + 10) . $gender . '.jpg');
            $manager->persist($user);
        }
        $manager->flush();

        // admin John Doe
        $user = new User();
        $user->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john.doe@gmail.com')
            ->setDisabled($faker->boolean(100))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($user, 'password'))
            ->setImage('066m.jpg');
        $manager->persist($user);
        $manager->flush();

        // Admin Jane Doe
        $user = new User();
        $user->setFirstName('Jane')
            ->setLastName('Doe')
            ->setEmail('jane.doe@gmail.com')
            ->setDisabled($faker->boolean(100))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($user, 'password'))
            ->setImage('066f.jpg');
        $manager->persist($user);
        $manager->flush();

        // Admin Tiger Woods
        $user = new User();
        $user->setFirstName('Tiger')
            ->setLastName('Woods')
            ->setEmail('tiger.woods@gmail.com')
            ->setDisabled($faker->boolean(100))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($user, 'password'))
            ->setImage('067m.jpg');
        $manager->persist($user);
        $manager->flush();

        // Super admin alessandro
        $user = new User();
        $user->setFirstName('Alessandro')
            ->setLastName('Sim')
            ->setEmail('aless.sim@gmail.com')
            ->setDisabled($faker->boolean(100))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setPassword($this->hasher->hashPassword($user, 'password'))
            ->setImage('064m.jpg');
        $manager->persist($user);
        $manager->flush();
    }
}
