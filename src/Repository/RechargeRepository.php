<?php

namespace App\Repository;

use App\Entity\Recharge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recharge>
 *
 * @method Recharge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recharge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recharge[]    findAll()
 * @method Recharge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RechargeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recharge::class);
    }

   /**
    * @return Recharge[] Returns an array of Recharge objects
    */
   public function findByEtatNull(): array
   {
       return $this->createQueryBuilder('r')
           ->andWhere('r.etat IS NULL')
           ->orderBy('r.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Recharge
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
