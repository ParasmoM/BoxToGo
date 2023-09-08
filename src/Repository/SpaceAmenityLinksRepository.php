<?php

namespace App\Repository;

use App\Entity\SpaceAmenityLinks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceAmenityLinks>
 *
 * @method SpaceAmenityLinks|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceAmenityLinks|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceAmenityLinks[]    findAll()
 * @method SpaceAmenityLinks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceAmenityLinksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceAmenityLinks::class);
    }

//    /**
//     * @return SpaceAmenityLinks[] Returns an array of SpaceAmenityLinks objects
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

//    public function findOneBySomeField($value): ?SpaceAmenityLinks
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
