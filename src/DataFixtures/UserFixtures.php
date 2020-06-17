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
    const STATUS = ['En attente', 'Validé'];
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
            $user->setPassword($faker->password);
            $user->setEmail($faker->email);
            $user->setDescription('golf');
            $user->setPhoneNumber($faker->e164PhoneNumber);
            $user->setActivity('industrie');
            $user->setCity('Orleans');
            $user->setStreet('5 rue des champs');
            $user->setPostalCode('45000');
            $user->setPicture('ambassadeur.jpg');
            $user->setEmploymentArea('Orleans');
            $user->setRoles();
            $user->setUpdatedAt(new DateTime());
            $user->setStatus(self::STATUS[random_int(0, 1)]);
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
        $user->setActivity('industrie');
        $user->setCity('Orleans');
        $user->setStreet('5 rue des champs');
        $user->setPostalCode('45000');
        $user->setPicture('ambassadeur.jpg');
        $user->setEmploymentArea('Orleans');
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
