<?php

namespace App\Repository;

use App\Entity\Delai;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Delai>
 *
 * @method Delai|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delai|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delai[]    findAll()
 * @method Delai[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelaiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Delai::class);
    }

//    /**
//     * @return Delai[] Returns an array of Delai objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Delai
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
