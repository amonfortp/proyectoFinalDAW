<?php

namespace App\Repository;

use App\Entity\Filtros;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Filtros|null find($id, $lockMode = null, $lockVersion = null)
 * @method Filtros|null findOneBy(array $criteria, array $orderBy = null)
 * @method Filtros[]    findAll()
 * @method Filtros[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FiltrosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Filtros::class);
    }

    // /**
    //  * @return Filtros[] Returns an array of Filtros objects
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
    public function findOneBySomeField($value): ?Filtros
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
