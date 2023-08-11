<?php

namespace App\Repository;

use App\Entity\SpaceReviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceReviews>
 *
 * @method SpaceReviews|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceReviews|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceReviews[]    findAll()
 * @method SpaceReviews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceReviewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceReviews::class);
    }

//    /**
//     * @return SpaceReviews[] Returns an array of SpaceReviews objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SpaceReviews
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
