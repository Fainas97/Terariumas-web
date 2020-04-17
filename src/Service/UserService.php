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

    public function getUsersNames($IdList)
    {
        $formattedResult = [];
        $userNames = $this->em->getRepository(User::class)->getUsersNames($IdList);
        foreach ($userNames as $userName) {
            $formattedResult[$userName['id']] = $userName['name'];
        }

        return $formattedResult;
    }
}