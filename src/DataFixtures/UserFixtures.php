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

    const ACTIVITIES = [
        'Aéronautique - défense',
        'Agroalimentaire',
        'Equipementiers automobile',
        'Centre d\'appels - Relation clients',
        'Energies nouvelles renouvelables',
        'Nucléaire',
        'Logistique - Transports',
        'Equipementiers machinisme agricoles',
        'Mécanique - Travaux des métaux',
        'Matériaux composites',
        'Banque assurance et mutuelle',
        'Objets connectés - IA - Electronique',
        'ESN',
        'Industrie graphique',
        'Parfumerie cosmétique',
        'Santé - Pharmacie',
        'Transformation du bois',
        'Economie scoiale et solidaire',
        'Autre',
    ];

    const EMPLOYMENT_AREA = [
        'Orléans',
        'Pithiviers',
        'Montargis',
        'Gien',
    ];

    const ROLES = [
      'ROLE_AMBASSADEUR',
      'ROLE_USER',
    ];

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
            $user->setDescription($faker->text(255));
            $user->setPhoneNumber($faker->e164PhoneNumber);
            $user->setActivity(self::ACTIVITIES[array_rand(self::ACTIVITIES, 1)]);
            $user->setCity($faker->city);
            $user->setStreet(ucfirst($faker->streetName));
            $user->setPostalCode($faker->postcode);
            $user->setEmploymentArea(self::EMPLOYMENT_AREA[array_rand(self::EMPLOYMENT_AREA, 1)]);
            $user->setRoles((array)self::ROLES[array_rand(self::ROLES, 1)]);
            $bool= random_int(0, 1);
            if ($bool === 1) {
                $user->setPicture('ambassadeur.jpeg');
                $user->setUpdatedAt(new DateTime());
                $user->setStatus('Validé');
            } else {
                $user->setStatus('En attente');
            }


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
