<?php

namespace App\Repository;

use App\Entity\Fournisseurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fournisseurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fournisseurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fournisseurs[]    findAll()
 * @method Fournisseurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FournisseursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseurs::class);
    }

    /**
    * @return Fournisseurs|null Returns an array of Fournisseurs objects
    */
    public function findOneById($value): ?Fournisseurs
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
    * @return Fournisseurs[] Returns an array of Fournisseurs objects
    */
    public function findByPays($value): ?Fournisseurs
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.pays = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Fournisseurs[] Returns an array of Fournisseurs objects
    */
    public function findByCodePostal($value): ?Fournisseurs
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.code_postal = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Fournisseurs|null Returns an array of Fournisseurs objects
    */
    public function findOneByLibelle($value): ?Fournisseurs
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.Libelle = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return Fournisseurs[] Returns an array of Fournisseurs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fournisseurs
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
