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
        '5f0f17afb34f6283035644.pdf',
        '5f086d1b32f6c034113258.doc',
        '5f1017bdbd351060044511.odt',
        '5f0f17876544d192125271.txt'
    ];
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 70; $i++) {
            $document = new Document();
            $document->setName($faker->word());
            $doc = self::FILE_NAME[array_rand(self::FILE_NAME, 1)];
            $document->setDocument($doc);
            switch ($doc) {
                case "5f0f17afb34f6283035644.pdf":
                    $document->setExt('pdf');
                    break ;
                case "5f086d1b32f6c034113258.doc":
                    $document->setExt('word');
                    break ;
                case "55f1017bdbd351060044511.odt":
                    $document->setExt('odt');
                    break ;
                case "5f0f17876544d192125271.txt":
                    $document->setExt('txt');
                    break;
                default:
                    $document->setExt('txt');
            }
            $document->setUpdatedAt(new DateTime());
            $manager->persist($document);
        }

        $manager->flush();
    }
}
