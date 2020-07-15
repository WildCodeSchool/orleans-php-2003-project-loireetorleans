<?php
namespace App\DataFixtures;

use App\Entity\Document;
use DateTime;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DocumentFixtures extends Fixture
{
    const FILE_NAME = [
        '5efedad63ce0f039578100.pdf',
        '5f086d1b32f6c034113258.doc',
        '5f086d4acbe77782503112.odt',
        '5f086d67618ad717679904.txt'
    ];
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 70; $i++) {
            $document = new Document();
            $document->setName($faker->word());
            $document->setDocument(array_rand(self::FILE_NAME, 1));
            switch ($document->getDocument()) {
                case "5efedad63ce0f039578100.pdf":
                    $document->setExt('pdf');
                    break ;
                case "5f086d1b32f6c034113258.doc":
                    $document->setExt('word');
                    break ;
                case "5f086d4acbe77782503112.odt":
                    $document->setExt('odt');
                    break ;
                case "5f086d67618ad717679904.txt":
                    $document->setExt('txt');

            }
            $document->setUpdatedAt(new DateTime());
            $manager->persist($document);
        }

        $manager->flush();
    }
}
