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

    public function getTerrariumsData()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT u.name as userName, t.name as terName, ter.temperature, ter.humidity, ter.light, ter.heater, ter.time 
            FROM `terrarium_data` as ter
            LEFT JOIN terrarium as t ON ter.terrarium_id = t.id
            LEFT JOIN user as u ON t.user_id = u.id
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getUserTerrariumsData($user_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT u.name as userName, t.name as terName, ter.temperature, ter.humidity, ter.light, ter.heater, ter.time 
            FROM `terrarium_data` as ter
            LEFT JOIN terrarium as t ON ter.terrarium_id = t.id
            LEFT JOIN user as u ON t.user_id = u.id
            WHERE u.id = ?
            ';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $user_id, "integer");
        $stmt->execute();

        return $stmt->fetchAll();
    }

}
