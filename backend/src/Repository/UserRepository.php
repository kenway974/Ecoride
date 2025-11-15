<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Base query pour récupérer un user avec toutes ses relations
     */
    private function baseQuery()
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.vehicles', 'v')->addSelect('v')
            ->leftJoin('u.preference', 'p')->addSelect('p')
            ->leftJoin('u.trips', 't')->addSelect('t')
            ->leftJoin('u.reservations', 'r')->addSelect('r')
            ->leftJoin('u.reviews', 'rev')->addSelect('rev');
    }

    /**
     * Récupère un user par ID
     */
    public function findUserWithRelationsById(int $id): ?User
    {
        return $this->baseQuery()
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère un user par email
     */
    public function findUserWithRelationsByEmail(string $email): ?User
    {
        return $this->baseQuery()
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère un user par username
     */
    public function findUserWithRelationsByUsername(string $username): ?User
    {
        return $this->baseQuery()
            ->andWhere('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}



