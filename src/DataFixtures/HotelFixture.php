<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Finder\Finder;

class HotelFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $finder = new Finder();
        $finder->files()->in('assets/images/hotels');

        $images = [];
        foreach ($finder as $file) {
            $images[] = 'hotels/' . $file->getFilename();
        }

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $hotel = new Hotel();
            $hotel->setName($faker->company);
            $hotel->setDescription($faker->text(200));
            $hotel->setLocation($faker->address);
            $hotel->setRating($faker->numberBetween(0, 6));
            $hotel->setPricePerNight($faker->numberBetween(9000000, 50000000));
            $hotel->setImage($images[array_rand($images)]);

            $manager->persist($hotel);
        }

        $manager->flush();
    }
}
