<?php

namespace App\Repository;

use App\Entity\Etiquetas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Etiquetas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etiquetas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etiquetas[]    findAll()
 * @method Etiquetas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtiquetasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etiquetas::class);
    }

    // /**
    //  * @return Etiquetas[] Returns an array of Etiquetas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Etiquetas
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
