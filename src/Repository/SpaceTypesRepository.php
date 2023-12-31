<?php

namespace App\Repository;

use App\Entity\SpaceTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpaceTypes>
 *
 * @method SpaceTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceTypes[]    findAll()
 * @method SpaceTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceTypes::class);
    }

    public function findSpaceTypeByName($name)
    {
        $queryBuilder = $this->createQueryBuilder('st')
            ->where('st.name_en LIKE :name_en OR st.name_fr LIKE :name_fr')
            ->setParameters([
                'name_en' => $name . '%',
                'name_fr' => $name . '%',
            ]);
    
        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return SpaceTypes[] Returns an array of SpaceTypes objects
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

//    public function findOneBySomeField($value): ?SpaceTypes
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
