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
    
        return $queryBuilder->getQuery()->getResult();
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
