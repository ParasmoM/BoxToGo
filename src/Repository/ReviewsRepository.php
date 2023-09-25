<?php

namespace App\Repository;

use App\Entity\Reviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reviews>
 *
 * @method Reviews|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reviews|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reviews[]    findAll()
 * @method Reviews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reviews::class);
    }

    public function calculateAverageRating()
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->select('AVG(r.rating) as averageRating')
            ->getQuery();

        $result = $queryBuilder->getSingleScalarResult();

        return (float)$result; // Convertir en float, car la moyenne peut avoir des décimales
    }

    public function getAverageRatingByCategory(string $categoryName)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->join('r.spaces', 's')
            ->leftJoin('s.type', 't')
            ->where('t.name = :categoryName')
            ->setParameter('categoryName', $categoryName)
            ->select('AVG(r.rating) as averageRating')
            ->getQuery();
    
        $result = $queryBuilder->getSingleScalarResult();
    
        return (float)$result; // Convertir en float, car la moyenne peut avoir des décimales
    }
    
    public function countByPeriod(string $period = 'week')
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->select('COUNT(r.id) as count');

        // $currentDate = new \DateTimeImmutable();

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
//     * @return Reviews[] Returns an array of Reviews objects
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

//    public function findOneBySomeField($value): ?Reviews
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
