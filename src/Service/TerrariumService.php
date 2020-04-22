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
        $terrarium->setTemperatureRange($form->get('Temperature_range')->getData());
        $terrarium->setHumidityRange($form->get('Humidity_range')->getData());
        $terrarium->setLightingSchedule($form->get('Lighting_schedule')->getData());
        $terrarium->setUrl($form->get('Url')->getData());
        $terrarium->setUpdateTime(new \DateTime('now'));

        return $terrarium;
    }

    public function prepareRPiData($data)
    {
        $tempList = explode(':', $data->getTemperatureRange());
        $humiList = explode(':', $data->getHumidityRange());
        $timeList = explode('-', $data->getLightingSchedule());

        $parameters = [];
        $parameters['temp_low'] = $tempList[0];
        $parameters['temp_high'] = $tempList[1];
        $parameters['humi_low'] = $humiList[0];
        $parameters['humi_high'] = $humiList[1];
        $parameters['time_light_start'] = $timeList[0];
        $parameters['time_light_end'] = $timeList[1];

        return $parameters;
    }
}