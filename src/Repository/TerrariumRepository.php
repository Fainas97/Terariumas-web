<?php

namespace App\Repository;

use App\Entity\Terrarium;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Terrarium|null find($id, $lockMode = null, $lockVersion = null)
 * @method Terrarium|null findOneBy(array $criteria, array $orderBy = null)
 * @method Terrarium[]    findAll()
 * @method Terrarium[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TerrariumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Terrarium::class);
    }

    // /**
    //  * @return Terrarium[] Returns an array of Terrarium objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Terrarium
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}