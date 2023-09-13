<?php

namespace App\Repository;

use App\Entity\HistoriqueDePaye;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoriqueDePaye>
 *
 * @method HistoriqueDePaye|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoriqueDePaye|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoriqueDePaye[]    findAll()
 * @method HistoriqueDePaye[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueDePayeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoriqueDePaye::class);
    }

   /**
    * @return HistoriqueDePaye[] Returns an array of HistoriqueDePaye objects
    */
   public function findAllPayeBySupervisor($id): array
   {
       return $this->createQueryBuilder('recharge')
           ->andWhere('recharge.superviseur = :val')
           ->setParameter('val', $id)
           ->orderBy('recharge.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?HistoriqueDePaye
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
