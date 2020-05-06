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
            ORDER BY ter.time DESC
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
            ORDER BY ter.time DESC
            ';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $user_id, "integer");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getLatestTerrariumData()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT terrarium_id, 
                round(avg(temperature), 2) as temperature, 
                round(avg(humidity), 2) as humidity
            FROM (
              SELECT
                terrarium_id,
                temperature,
                humidity,
                time,
                DENSE_RANK() OVER (PARTITION BY terrarium_id ORDER BY time DESC) AS rank
              FROM terrarium_data
            ) AS ter
            WHERE ter.rank <= 10
            GROUP BY terrarium_id;
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}
