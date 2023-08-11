<?php

namespace App\Repository;

use App\Entity\SpaceImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceImages>
 *
 * @method SpaceImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceImages[]    findAll()
 * @method SpaceImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceImages::class);
    }

//    /**
//     * @return SpaceImages[] Returns an array of SpaceImages objects
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

//    public function findOneBySomeField($value): ?SpaceImages
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
