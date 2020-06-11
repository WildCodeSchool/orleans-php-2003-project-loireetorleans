<?php


namespace App\DataFixtures;

use App\Entity\Card;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AmbassadorFixture extends \Doctrine\Bundle\FixturesBundle\Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 70; $i++) {
            $card = new Card();
            $card->setNom($faker->firstName);
            $card->setPrenom($faker->lastName);
            $card->setEntreprise($faker->company);

            $manager->persist($card);
        }

        $manager->flush();
    }
}
