<?php

namespace App\Repository;

use App\Entity\DemandeDeValidation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeDeValidation>
 *
 * @method DemandeDeValidation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeDeValidation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeDeValidation[]    findAll()
 * @method DemandeDeValidation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeDeValidationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeDeValidation::class);
    }

//    /**
//     * @return DemandeDeValidation[] Returns an array of DemandeDeValidation objects
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

//    public function findOneBySomeField($value): ?DemandeDeValidation
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
