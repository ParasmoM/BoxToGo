<?php

namespace App\Repository;

use App\Entity\FavoriteSpaces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoriteSpaces>
 *
 * @method FavoriteSpaces|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteSpaces|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteSpaces[]    findAll()
 * @method FavoriteSpaces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteSpacesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteSpaces::class);
    }

//    /**
//     * @return FavoriteSpaces[] Returns an array of FavoriteSpaces objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FavoriteSpaces
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
