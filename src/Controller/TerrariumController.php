<?php

namespace App\Controller;

use App\Entity\Terrarium;
use App\Form\AddTerrariumFormType;
use App\Service\TerrariumService;
use App\Service\UserService;
use phpDocumentor\Reflection\DocBlock\Tags\Formatter\AlignFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TerrariumController extends AbstractController
{

    /**
     * @Route("/terrariums", name="terrariums_show")
     * @param TerrariumService $terrariumService
     * @return Response
     */
    public function terrariums(TerrariumService $terrariumService, UserService $userService): Response
    {
        $user = $this->getUser();
        if ($user->getAdmin()) {
            $terrariums = $terrariumService->getTerrariums();
            $userNames = $this->getUserNames($terrariums, $userService);

            return $this->render('terrarium/terrariums.html.twig', [
                'terrariums' => $terrariums,
                'userNames' => $userNames
            ]);
        }
    }

    /**
     * @Route("/terrariums/create", name="create_terrariums")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $terrarium = new Terrarium();
        $form = $this->createForm(AddTerrariumFormType::class, $terrarium);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $terrarium->setUserId($form->get('Users')->getData()->getId());
            $terrarium->setSettings(
                json_encode($form->get('Settings')->getData())
            );
            $terrarium->setCreatedTime(new \DateTime('now'));
            $terrarium->setUpdateTime(new \DateTime('now'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($terrarium);
            $entityManager->flush();

            $this->addFlash('success', 'Terrarium has been added successfully!');

            return $this->redirectToRoute('terrariums_show');
        }

        return $this->render('terrarium/create.html.twig', [
            'terrariumCreateForm' => $form->createView(),
        ]);
    }

    /**
     * @param $terrariums
     * @param $userService
     * @return array
     */
    private function getUserNames($terrariums, $userService)
    {
        $idList = [];
        foreach ($terrariums as $terrarium) {
            $idList[$terrarium->getUserId()] = $terrarium->getUserId();
        }

        return $userNames = $userService->getUsersNames($idList);
    }
}