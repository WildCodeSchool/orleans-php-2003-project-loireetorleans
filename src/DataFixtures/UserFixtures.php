<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $subscriber = new User();
        $subscriber->setLogin('hadef');
        $subscriber->setRoles(['ROLE_AMBASSADEUR']);
        $subscriber->setFirstname($faker->firstName);
        $subscriber->setLastname($faker->lastName);
        $subscriber->setEmail($faker->email);
        $subscriber->setDescription('golf');
        $subscriber->setPhoneNumber($faker->e164PhoneNumber);
        $subscriber->setPicture('http://lorempixel.com/gray/800/400/cats/Faker/');
        $subscriber->setCompany($faker->company);
        $subscriber->setActivity('industrie');
        $subscriber->setCity('Orleans');
        $subscriber->setStreet('5 rue des champs');
        $subscriber->setPostalCode('45000');
        $subscriber->setPassword($this->passwordEncoder->encodePassword(
            $subscriber,
            'loireetorleans'
        ));

        $manager->persist($subscriber);

        $manager->flush();
    }
}
