<?php
namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
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
        for ($i = 0; $i <= 70; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setCompany($faker->company);
            $user->setLogin('wilder' . $i);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'loireetorleans'
            ));
            $user->setEmail($faker->email);
            $user->setDescription($faker->word);
            $user->setPhoneNumber($faker->e164PhoneNumber);
            $user->setActivity($faker->jobTitle);
            $user->setCity($faker->city);
            $user->setNumber($faker->numberBetween(0, 500));
            $user->setStreet(ucfirst($faker->streetName));
            $user->setPostalCode($faker->postcode);
            $user->setEmploymentArea($faker->city);
            $user->setRoles(['ROLE_USER']);
            $user->setUpdatedAt(new DateTime());
            $user->setStatus('En attente');
            $manager->persist($user);
        }
        $user->setLogin('hadef');
        $user->setRoles(['ROLE_ADMINISTRATEUR']);
        $user->setFirstname($faker->firstName);
        $user->setLastName($faker->lastName);
        $user->setCompany($faker->company);
        $user->setEmail($faker->email);
        $user->setDescription('golf');
        $user->setPhoneNumber($faker->e164PhoneNumber);
        $user->setActivity($faker->jobTitle);
        $user->setCity($faker->city);
        $user->setStreet($faker->streetName);
        $user->setPostalCode($faker->postcode);
        $user->setPicture('ambassadeur.jpg');
        $user->setEmploymentArea($faker->city);
        $user->setUpdatedAt(new DateTime());
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'loireetorleans'
        ));
        $user->setStatus('Validé');
        $manager->persist($user);
        $manager->flush();
    }
}
