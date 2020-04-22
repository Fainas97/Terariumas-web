<?php

namespace App\Controller;

use Exception;
use App\Entity\TerrariumData;
use App\Service\TerrariumService;
use App\Service\TerrariumDataService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TerrariumDataController extends AbstractController
{
    /**
     * @Route("/terrariums/data", name="home_page")
     * @IsGranted("ROLE_USER", message="Only users access this page")
     * @param TerrariumDataService $terrariumDataService
     * @return Response
     */
    public function index(TerrariumDataService $terrariumDataService)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $terrariumsData = $terrariumDataService->getTerrariumsData();

            return $this->render('dashboard/home.html.twig', [
                'terrariumsData' => $terrariumsData
            ]);
        }
        $terrariumsData = $terrariumDataService->getUsersTerrariumsData($this->getUser()->getId());

        return $this->render('dashboard/home.html.twig', [
            'terrariumsData' => $terrariumsData
        ]);
    }

    /**
     * @Route("/terrarium/data")
     * @param Request $request
     * @param TerrariumService $terrariumService
     * @return Response
     * @throws Exception
     */
    public function receiveData(Request $request, TerrariumService $terrariumService)
    {
        $receivedRequest = $request->request->all();
        $terrarium = $terrariumService->getTerrariumByAuth($receivedRequest['auth']);

        if ($terrarium) {
            $this->saveTerrariumData($receivedRequest, $terrarium->getId());

            return new JsonResponse(array('success'));
        }

        return new JsonResponse(array('false'));
    }

    private function saveTerrariumData($receivedRequest, $id)
    {
        $terrariumData = new TerrariumData();
        $terrariumData->setTerrariumId($id);
        $terrariumData->setHumidity($receivedRequest['humidity']);
        $terrariumData->setTemperature($receivedRequest['temperature']);
        $terrariumData->setLight($receivedRequest['light']);
        $terrariumData->setHeater($receivedRequest['heater']);
        $terrariumData->setTime(new \DateTime('now'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($terrariumData);
        $entityManager->flush();
    }

}