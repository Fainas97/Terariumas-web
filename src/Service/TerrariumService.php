<?php

namespace App\Service;

use App\Entity\Terrarium;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;

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

    public function getTerrarium($id)
    {
        return $this->em->getRepository(Terrarium::class)->getTerrarium($id);
    }

    public function getUserTerrariums($user_id)
    {
        return $this->em->getRepository(Terrarium::class)->getUserTerrarium($user_id);
    }

    public function getTerrariumByAuth($auth)
    {
        return $this->em->getRepository(Terrarium::class)->findOneBy(['auth' => $auth]);
    }

    public function prepareTerrariumData(Terrarium $terrarium, FormInterface $form)
    {
        $terrarium->setUserId($form->get('Users')->getData()->getId());
        $terrarium->setSettings(
            json_encode($form->get('Settings')->getData())
        );
        $terrarium->setUpdateTime(new \DateTime('now'));

        return $terrarium;
    }
}