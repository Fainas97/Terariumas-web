<?php

namespace App\Service;

use App\Entity\Terrarium;
use Doctrine\ORM\EntityManager;

class TerrariumService
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getTerrariums()
    {
        return $this->em->getRepository(Terrarium::class)->getTerrariums();
    }

}