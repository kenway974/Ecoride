<?php

namespace App\Repository;

use App\Entity\Preference;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Preference>
 */
class PreferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Preference::class);
    }

    /**
     * Récupère les préférences d’un utilisateur
     */
    public function findByUser(User $user): ?Preference
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // ------------------------
    // Exemples de méthodes personnalisées
    // ------------------------

    /**
     * @return Preference[]
     */
    public function findByOption(string $key, $value): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere("p.options->>:key = :value")
           ->setParameter('key', $key)
           ->setParameter('value', $value);

        return $qb->getQuery()->getResult();
    }

    // Tu peux ajouter d’autres méthodes spécifiques à Preference ici
}
