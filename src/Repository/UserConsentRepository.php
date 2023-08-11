<?php

namespace App\Repository;

use App\Entity\UserConsent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserConsent>
 *
 * @method UserConsent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserConsent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserConsent[]    findAll()
 * @method UserConsent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserConsentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserConsent::class);
    }

//    /**
//     * @return UserConsent[] Returns an array of UserConsent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserConsent
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
