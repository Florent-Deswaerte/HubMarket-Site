<?php

namespace App\Repository;

use App\Entity\Commandes;
use App\Entity\Utilisateurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commandes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commandes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commandes[]    findAll()
 * @method Commandes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commandes::class);
    }

    /**
    * @return Commandes
    */
    public function findOneById($id)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
    * @return Commandes
    */
    public function findOneByStatus($id)
    {
        return $this->createQueryBuilder('c')
            ->Where('c.status_stripe is null')
            ->andWhere('c.utilisateurs = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
    * @return Commandes
    */
    public function findOneByStatusDifferentNull($id)
    {
        return $this->createQueryBuilder('c')
            ->Where('c.status_stripe is not null')
            ->andWhere('c.utilisateurs = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
    * @return Commandes
    */
    public function findSomme($id)
    {
        return $this->createQueryBuilder('c')
            ->select('sum(c.TotalCommande)')
            ->andWhere('c.utilisateurs = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Commandes
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
