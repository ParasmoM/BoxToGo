<?php

namespace App\Repository;

use App\Entity\ArticleLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleLog>
 *
 * @method ArticleLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleLog[]    findAll()
 * @method ArticleLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleLog::class);
    }

//    /**
//     * @return ArticleLog[] Returns an array of ArticleLog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ArticleLog
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
