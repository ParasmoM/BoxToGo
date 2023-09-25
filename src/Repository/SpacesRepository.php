<?php

namespace App\Repository;

use App\Entity\Spaces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Spaces>
 *
 * @method Spaces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spaces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spaces[]    findAll()
 * @method Spaces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpacesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spaces::class);
    }

    public function save(Spaces $space): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($space);
        $entityManager->flush();
    }

    public function findSpacesByPostalOrCity($data)
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->innerJoin('s.adresse', 'a'); // s.adresse correspond au champ 'adresse' dans votre entité 'Spaces'
    
        if (is_numeric($data)) {
            $queryBuilder
                ->where('a.postalCode = :data') // a.postalCode correspond au champ 'postalCode' dans votre entité 'Addresses'
                ->setParameter('data', $data);
        } else {
            $queryBuilder
                ->where('a.city LIKE :data') // a.city correspond au champ 'city' dans votre entité 'Addresses'
                ->setParameter('data', $data . '%');
        }
    
        return $queryBuilder->getQuery()->getResult();
    }
    
    public function findSpacesByUserStatus($status)
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->innerJoin('s.ownedByUser', 'u') // s.ownedByUser correspond au champ 'ownedByUser' dans votre entité 'Spaces'
            ->where('u.status = :status') // u.status correspond au champ 'status' dans votre entité 'User'
            ->setParameter('status', $status);
    
            // dd($queryBuilder->getQuery()->getResult());
        return $queryBuilder->getQuery()->getResult();
    }
    
    public function countByPeriod(string $period = 'week')
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->select('COUNT(s.id) as count');

        switch ($period) {
            case 'week':
                $queryBuilder->where('s.createAt >= :startOfWeek')
                    ->setParameter('startOfWeek', new \DateTimeImmutable('monday this week'));
                break;

            case 'month':
                $queryBuilder->where('s.createAt >= :startOfMonth')
                    ->setParameter('startOfMonth', new \DateTimeImmutable('first day of this month'));
                break;

            case 'year':
                $queryBuilder->where('s.createAt >= :startOfYear')
                    ->setParameter('startOfYear', new \DateTimeImmutable('first day of January this year'));
                break;

            default:
                throw new \InvalidArgumentException('Invalid period');
        }

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return (int) $result;
    }
//    /**
//     * @return Spaces[] Returns an array of Spaces objects
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

//    public function findOneBySomeField($value): ?Spaces
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
