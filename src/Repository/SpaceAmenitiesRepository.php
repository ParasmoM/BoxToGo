<?php

namespace App\Repository;

use App\Entity\SpaceAmenities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceAmenities>
 *
 * @method SpaceAmenities|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceAmenities|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceAmenities[]    findAll()
 * @method SpaceAmenities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceAmenitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceAmenities::class);
    }

//    /**
//     * @return SpaceAmenities[] Returns an array of SpaceAmenities objects
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

//    public function findOneBySomeField($value): ?SpaceAmenities
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
