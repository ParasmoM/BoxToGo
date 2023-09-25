<?php

namespace App\Repository;

use App\Entity\Reservations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservations>
 *
 * @method Reservations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservations[]    findAll()
 * @method Reservations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservations::class);
    }

    public function countByPeriod(string $period = 'week')
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->select('COUNT(r.id) as count');

        switch ($period) {
            case 'week':
                $queryBuilder->where('r.createAt >= :startOfWeek')
                    ->setParameter('startOfWeek', new \DateTimeImmutable('monday this week'));
                break;

            case 'month':
                $queryBuilder->where('r.createAt >= :startOfMonth')
                    ->setParameter('startOfMonth', new \DateTimeImmutable('first day of this month'));
                break;

            case 'year':
                $queryBuilder->where('r.createAt >= :startOfYear')
                    ->setParameter('startOfYear', new \DateTimeImmutable('first day of January this year'));
                break;

            default:
                throw new \InvalidArgumentException('Invalid period');
        }

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return (int) $result;
    }
//    /**
//     * @return Reservations[] Returns an array of Reservations objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservations
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
