<?php

namespace App\DataFixtures;

use App\Entity\User;
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
        $subscriber = new User();
        $subscriber->setLogin('toto');
        $subscriber->setRoles(['ROLE_SUBSCRIBER']);
        $subscriber->setFirstname('toto');
        $subscriber->setLastname('toto2');
        $subscriber->setEmail('toto@maggle.fr');
        $subscriber->setDescription('golf');
        $subscriber->setPhoneNumber('3615ULA');
        $subscriber->setPicture('dqedazeazee');
        $subscriber->setCompany('Wild');
        $subscriber->setActivity('industrie');
        $subscriber->setCity('Orleans');
        $subscriber->setStreet('5 rue des champs');
        $subscriber->setPostalCode('45000');
        $subscriber->setPassword($this->passwordEncoder->encodePassword(
            $subscriber,
            'rototo'
        ));

        $manager->persist($subscriber);

        $manager->flush();
    }
}
