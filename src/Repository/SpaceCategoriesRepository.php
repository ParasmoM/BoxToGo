<?php

namespace App\Repository;

use App\Entity\SpaceCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceCategories>
 *
 * @method SpaceCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceCategories[]    findAll()
 * @method SpaceCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceCategories::class);
    }

//    /**
//     * @return SpaceCategories[] Returns an array of SpaceCategories objects
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

//    public function findOneBySomeField($value): ?SpaceCategories
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
