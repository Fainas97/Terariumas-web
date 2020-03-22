<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUsers($adminId)
    {
        return $this->createQueryBuilder('u')
            ->where('u.id != :val')
            ->setParameter('val', $adminId)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getUser($Id)
    {
        return $this->createQueryBuilder('u')
            ->where('u.id = :val')
            ->setParameter('val', $Id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
