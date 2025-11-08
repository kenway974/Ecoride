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
     * Recherche des trips par villes, date et places restantes
     * Récupère également le driver, sa préférence et le véhicule du trip
     *
     * @param string|null $from Ville de départ
     * @param string|null $to Ville d’arrivée
     * @param string|null $date YYYY-MM-DD
     * @return array tableau prêt à JSON
     */
    public function findByCityAndDateSafe(?string $from, ?string $to, ?string $date): array
    {
        $qb = $this->createQueryBuilder('t')
            ->leftJoin('t.driver', 'd')
            ->addSelect('d')
            ->leftJoin('d.preference', 'dp') // Relation OneToOne Driver → Preference
            ->addSelect('dp')
            ->leftJoin('t.vehicle', 'v') // Relation Trip → Vehicle
            ->addSelect('v');

        // Filtre par ville de départ
        if ($from) {
            $qb->andWhere('t.startCity LIKE :from')
               ->setParameter('from', '%' . $from . '%');
        }

        // Filtre par ville d’arrivée
        if ($to) {
            $qb->andWhere('t.arrivalCity LIKE :to')
               ->setParameter('to', '%' . $to . '%');
        }

        // Filtre par date
        if ($date) {
            try {
                $dateObj = new \DateTime($date);
                $nextDay = (clone $dateObj)->modify('+1 day');

                $qb->andWhere('t.departureDate >= :start AND t.departureDate < :end')
                   ->setParameter('start', $dateObj->format('Y-m-d'))
                   ->setParameter('end', $nextDay->format('Y-m-d'));
            } catch (\Exception $e) {
                // Date invalide, on ignore le filtre
            }
        }

        // Filtre par nombre de places restantes
        $qb->andWhere('t.seatsRemaining > 0');

        // Retourne le résultat sous forme de tableau prêt pour JSON
        return $qb->getQuery()->getArrayResult();
    }
}
