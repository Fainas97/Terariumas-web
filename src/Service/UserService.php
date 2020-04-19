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

    public function getUsersNames($terrariums)
    {
        $idList = [];
        foreach ($terrariums as $terrarium) {
            $idList[$terrarium->getUserId()] = $terrarium->getUserId();
        }

        $formattedResult = [];
        $userNames = $this->em->getRepository(User::class)->getUsersNames($idList);
        foreach ($userNames as $userName) {
            $formattedResult[$userName['id']] = $userName['name'];
        }

        return $formattedResult;
    }
}