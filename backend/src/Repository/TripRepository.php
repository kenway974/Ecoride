<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /**
     * Recherche des trips par villes et date (safe)
     *
     * @param string|null $from startCity
     * @param string|null $to arrivalCity
     * @param string|null $date YYYY-MM-DD
     * @return array tableau prêt à JSON
     */
    public function findByCityAndDateSafe(?string $from, ?string $to, ?string $date): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($from) {
            $qb->andWhere('t.startCity LIKE :from')
               ->setParameter('from', '%' . $from . '%');
        }

        if ($to) {
            $qb->andWhere('t.arrivalCity LIKE :to')
               ->setParameter('to', '%' . $to . '%');
        }

        if ($date) {
            try {
                $dateObj = new \DateTime($date);
                $nextDay = (clone $dateObj)->modify('+1 day');

                $qb->andWhere('t.departureDate >= :start AND t.departureDate < :end')
                ->setParameter('start', $dateObj->format('Y-m-d'))
                ->setParameter('end', $nextDay->format('Y-m-d'));
            } catch (\Exception $e) {
            }
        }


        return $qb->getQuery()->getArrayResult();
    }
}
