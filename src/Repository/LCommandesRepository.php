<?php

namespace App\Repository;

use App\Entity\LCommandes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LCommandes|null find($id, $lockMode = null, $lockVersion = null)
 * @method LCommandes|null findOneBy(array $criteria, array $orderBy = null)
 * @method LCommandes[]    findAll()
 * @method LCommandes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LCommandesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LCommandes::class);
    }

    // /**
    //  * @return LCommandes[] Returns an array of LCommandes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LCommandes
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
