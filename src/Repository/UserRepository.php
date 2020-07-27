<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    public function findAllValidateWhithoutAdmin()
    {
        return $this->createQueryBuilder('u')
            ->where('u.status = :status ')
            ->setParameter('status', 'Validé')
            ->andWhere('u.roles NOT LIKE :role')
            ->setParameter('role', '%ROLE_ADMINISTRATEUR%')
            ->getQuery()
            ->getResult();
    }

    public function findAllWaitingWhithoutAdmin()
    {
        return $this->createQueryBuilder('u')
            ->where('u.status = :status ')
            ->setParameter('status', 'En attente')
            ->andWhere('u.roles NOT LIKE :role')
            ->setParameter('role', '%ROLE_ADMINISTRATEUR%')
            ->getQuery()
            ->getResult();
    }

    public function findTwoForMessage(string $login)
    {
        return $this->createQueryBuilder('u')
            ->Where('u.login LIKE :login')
            ->orWhere('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_ADMINISTRATEUR%')
            ->setParameter('login', $login)
            ->getQuery()
            ->execute();
    }




    public function findBySearch(?string $search)
    {
        return $this->createQueryBuilder('u')
            ->Where('u.firstname LIKE :search')
            ->orWhere('u.lastName LIKE :search')
            ->orWhere('u.company LIKE :search')
            ->orWhere('u.employmentArea LIKE :search')
            ->orWhere('u.activity LIKE :search')
            ->orWhere('u.description LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->andWhere('u.status = :status ')
            ->setParameter('status', 'Validé')
            ->getQuery()
            ->getResult();
    }

    public function findBySearchWhithoutAdmin(?string $search)
    {
        return $this->createQueryBuilder('u')
            ->Where('u.firstname LIKE :search')
            ->orWhere('u.lastName LIKE :search')
            ->orWhere('u.company LIKE :search')
            ->orWhere('u.employmentArea LIKE :search')
            ->orWhere('u.activity LIKE :search')
            ->orWhere('u.description LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->andWhere('u.roles NOT LIKE :role')
            ->setParameter('role', '%ROLE_ADMINISTRATEUR%')
            ->getQuery()
            ->getResult();
    }

    public function findBySearchValidatesWhithoutAdmin(?string $search)
    {
        return $this->createQueryBuilder('u')
            ->where('u.status = :status ')
            ->setParameter('status', 'Validé')
            ->andWhere('u.firstname LIKE :search')
            ->orWhere('u.lastName LIKE :search')
            ->orWhere('u.company LIKE :search')
            ->orWhere('u.employmentArea LIKE :search')
            ->orWhere('u.activity LIKE :search')
            ->orWhere('u.description LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->andWhere('u.roles NOT LIKE :role')
            ->setParameter('role', '%ROLE_ADMINISTRATEUR%')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
