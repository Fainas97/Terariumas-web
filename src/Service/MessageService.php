<?php

namespace App\Service;

use App\Entity\Message;
use Doctrine\ORM\EntityManager;

class MessageService
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getTerrariumsMessages()
    {
        return $this->em->getRepository(Message::class)->getTerrariumsMessages();
    }

}