<?php

namespace App\Repository;

use App\Entity\SpaceTranslations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceTranslations>
 *
 * @method SpaceTranslations|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceTranslations|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceTranslations[]    findAll()
 * @method SpaceTranslations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceTranslationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceTranslations::class);
    }

//    /**
//     * @return SpaceTranslations[] Returns an array of SpaceTranslations objects
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

//    public function findOneBySomeField($value): ?SpaceTranslations
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
