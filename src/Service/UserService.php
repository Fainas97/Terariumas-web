<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserService
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUsers($adminId)
    {
        return $this->em->getRepository(User::class)->getUsers($adminId);
    }

    public function getUser($Id)
    {
        return $this->em->getRepository(User::class)->getUser($Id);
    }
}