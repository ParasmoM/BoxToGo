<?php

namespace App\Repository;

use App\Entity\SpaceEquipements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceEquipements>
 *
 * @method SpaceEquipements|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceEquipements|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceEquipements[]    findAll()
 * @method SpaceEquipements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceEquipementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceEquipements::class);
    }

//    /**
//     * @return SpaceEquipements[] Returns an array of SpaceEquipements objects
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

//    public function findOneBySomeField($value): ?SpaceEquipements
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
