<?php

namespace App\Service;

use App\Entity\TerrariumData;
use Doctrine\ORM\EntityManager;

class TerrariumDataService
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getTerrariumsData()
    {
        return $this->em->getRepository(TerrariumData::class)->getTerrariumsData();
    }

    public function getUserTerrariumsData($userId)
    {
        return $this->em->getRepository(TerrariumData::class)->getUserTerrariumsData($userId);
    }

}