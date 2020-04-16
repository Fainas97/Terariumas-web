<?php

namespace App\Repository;

use App\Entity\TerrariumData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TerrariumData|null find($id, $lockMode = null, $lockVersion = null)
 * @method TerrariumData|null findOneBy(array $criteria, array $orderBy = null)
 * @method TerrariumData[]    findAll()
 * @method TerrariumData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TerrariumDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TerrariumData::class);
    }

    // /**
    //  * @return TerrariumData[] Returns an array of TerrariumData objects
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
    public function findOneBySomeField($value): ?TerrariumData
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
