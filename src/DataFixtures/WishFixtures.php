<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->realText($maxNbChars = 20));
            $wish->setDescription($faker->paragraph(2));
            $wish->setAuthor($faker->randomElement(['Jean Valjean', 'Silvester Stubborn', 'Guillaume Cané', 'Joseph Allin', 'Mahatmah Grandit', 'David Kronemburg']));
            $wish->setDateCreated(new \DateTimeImmutable('now'));
            $wish->setIsPublished(true);

            $manager->persist($wish);
        }

        $manager->flush();
    }
}

