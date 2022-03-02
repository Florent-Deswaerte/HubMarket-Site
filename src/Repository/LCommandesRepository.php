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

    public function findOneByCommande($id)
    {
        return $this->createQueryBuilder('c')
            ->Where('c.Commandes = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

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
