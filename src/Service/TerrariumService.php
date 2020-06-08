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
        $parameters['temp_limit'] = $tempList[0];
        $parameters['temp_hysteresis'] = $tempList[1];
        $parameters['humi_limit'] = $humiList[0];
        $parameters['humi_hysteresis'] = $humiList[1];
        $parameters['time_light_start'] = $timeList[0];
        $parameters['time_light_end'] = $timeList[1];

        return $parameters;
    }

    /**
     * @param array $terrariums
     * @return array
     */
    public function getIndicatorsLimits(array $terrariums)
    {
        $limits = [];
        foreach ($terrariums as $terrarium) {
            $tempRange = $terrarium->getTemperatureRange();
            $humiRange = $terrarium->getHumidityRange();

            $tempLimits['temp_high'] = $this->addOrSubtractValues($tempRange);
            $tempLimits['temp_low'] = $this->addOrSubtractValues($tempRange, false);
            $tempLimits['humi_high'] = $this->addOrSubtractValues($humiRange);
            $tempLimits['humi_low'] = $this->addOrSubtractValues($humiRange, false);
            $tempLimits['name'] = $terrarium->getName();
            $tempLimits['address'] = $terrarium->getAddress();
            $limits[$terrarium->getId()] = $tempLimits;
        }

        return $limits;
    }

    /**
     * @param string $range
     * @param bool $isAddOperation
     * @return string
     */
    private function addOrSubtractValues(string $range, bool $isAddOperation = true)
    {
        $values = explode(':', $range);

        return $isAddOperation ? $values[0] + $values[1] : $values[0] - $values[1];
    }

    /**
     * @param array $indicators
     * @param array $averages
     * @return array
     */
    public function calculateRoutes(array $indicators, array $averages)
    {
        $routes = [];
        foreach ($averages as $average) {
            $id = $average['terrarium_id'];

            $routes[$id]['terrarium'] = $indicators[$id]['name'];
            $routes[$id]['address'] = $indicators[$id]['address'];
            $routes[$id]['temperature'] = $average['temperature'];
            $routes[$id]['humidity'] = $average['humidity'];
            $routes[$id]['temperature_limit'] = $indicators[$id]['temp_low'] . ' - ' . $indicators[$id]['temp_high'];
            $routes[$id]['humidity_limit'] = $indicators[$id]['humi_low'] . ' - ' . $indicators[$id]['humi_high'];
            $routes[$id]['rating'] = $this->getDiffValue($average, $indicators, 'temperature', 'temp_high', 'temp_low')
                + $this->getDiffValue($average, $indicators, 'humidity', 'humi_high', 'humi_low');

            if ($routes[$id]['rating'] == 0) {
                unset($routes[$id]);
                continue;
            }
            $this->getConditionAndStatus($routes, $id);
        }
        $rating_column = array_column($routes, 'rating');
        array_multisort( $rating_column, SORT_DESC, $routes);

        return $routes;
    }

    /**
     * @param array $average
     * @param array $indicators
     * @param string $type
     * @param string $high
     * @param string $low
     * @return string|int
     */
    private function getDiffValue(array $average, array $indicators, string $type, string $high, string $low)
    {
        if ($average[$type] >= $indicators[$average['terrarium_id']][$high]) {
            return abs(number_format(1 - $average[$type] / $indicators[$average['terrarium_id']][$high], 2));
        } elseif ($average[$type] <= $indicators[$average['terrarium_id']][$low]) {
            return abs(number_format(1 - $average[$type] / $indicators[$average['terrarium_id']][$low], 2));
        } else {
            return 0;
        }
    }

    /**
     * @param array $routes
     * @param int $id
     */
    private function getConditionAndStatus(array &$routes, int $id)
    {
        if ($routes[$id]['rating'] <= 0.25) {
            $routes[$id]['condition'] = '#92B93D';
            $routes[$id]['status'] = 'Nepatenkinamas';
        } elseif ($routes[$id]['rating'] <= 0.6) {
            $routes[$id]['condition'] = '#B29307';
            $routes[$id]['status'] = 'Sunkus';
        } else {
            $routes[$id]['condition'] = '#D8210F';
            $routes[$id]['status'] = 'Kritinis';
        }
    }

}