<?php

namespace App\Controller;

use App\Entity\Terrarium;
use App\Form\TerrariumForm;
use App\Service\TerrariumService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @IsGranted("ROLE_USER", message="Only users access this page")
 */
class TerrariumController extends AbstractController
{
    /**
     * @Route("/terrariums", name="terrariums_show")
     * @IsGranted("ROLE_USER", message="Only users access this page")
     * @param TerrariumService $terrariumService
     * @param UserService $userService
     * @return Response
     */
    public function terrariums(TerrariumService $terrariumService, UserService $userService): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $terrariums = $terrariumService->getTerrariums();
            $userNames = $userService->getUsersNames($terrariums);

            return $this->render('terrarium/terrariums.html.twig', [
                'terrariums' => $terrariums,
                'userNames' => $userNames
            ]);
        }

        $terrariums = $terrariumService->getUserTerrariums($this->getUser()->getId());
        $userNames = $userService->getUsersNames($terrariums);

        return $this->render('terrarium/terrariums.html.twig', [
            'terrariums' => $terrariums,
            'userNames' => $userNames
        ]);
    }

    /**
     * @Route("/terrariums/create", name="create_terrariums")
     * @IsGranted("ROLE_ADMIN", message="Only administrator can access this page")
     * @param Request $request
     * @param TerrariumService $terrariumService
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, TerrariumService $terrariumService): Response
    {
        $terrarium = new Terrarium();
        $form = $this->createForm(TerrariumForm::class, $terrarium);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->httpRequest($form, $terrarium, $terrariumService);
            if ($response == 'Received') {
                $this->createTerrarium($terrarium, $form, $terrariumService);

                return $this->redirectToRoute('terrariums_show');
            }
            $this->addFlash('error', 'Terrarium settings were not uploaded to Raspberry!');

            return $this->redirectToRoute('terrariums_show');
        }

        return $this->render('terrarium/terrarium-management.html.twig', [
            'title' => 'Add terrarium',
            'terrariumForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/terrarium/edit/{id}", name="edit_terrarium")
     * @IsGranted("ROLE_ADMIN", message="Only administrator can access this page")
     * @param Request $request
     * @param int $id
     * @param TerrariumService $terrariumService
     * @return Response
     */
    public function edit(Request $request, int $id, TerrariumService $terrariumService): Response
    {
        $terrarium = $terrariumService->getTerrarium($id);
        $form = $this->createForm(TerrariumForm::class, $terrarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->httpRequest($form, $terrarium, $terrariumService);
            if ($response == 'Received') {
                $this->updateTerrarium($terrarium, $form, $terrariumService);

                return $this->redirectToRoute('terrariums_show');
            }
            $this->addFlash('error', 'Terrarium settings were not uploaded to Raspberry!');

            return $this->redirectToRoute('terrariums_show');
        }

        return $this->render('terrarium/terrarium-management.html.twig', [
            'title' => 'Edit terrarium',
            'terrariumForm' => $form->createView(),
            'terrarium' => $terrarium
        ]);
    }

    /**
     * @Route("/terrarium/delete/{id}", name="delete_terrarium")
     * @IsGranted("ROLE_ADMIN", message="Only administrator can access this page")
     * @param int $id
     * @param TerrariumService $terrariumService
     * @return Response
     */
    public function delete(int $id, TerrariumService $terrariumService): Response
    {
        $terrarium = $terrariumService->getTerrarium($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($terrarium);
        $entityManager->flush();

        return new JsonResponse(array('success' => 'Terrarium "' . $terrarium->getName() . '" has been removed!'));
    }

    /**
     * @Route("/terrariums/table", name="terrariums_table", options={"expose" = true})
     * @IsGranted("ROLE_ADMIN", message="Only administrator can access this page")
     * @param TerrariumService $terrariumService
     * @param UserService $userService
     * @return Response
     */
    public function terrariumsTable(TerrariumService $terrariumService, UserService $userService)
    {
        $terrariums = $terrariumService->getTerrariums();

        return new Response($this->renderView('table/terrarium-table.html.twig', [
            'terrariums' => $terrariums,
            'userNames' => $userService->getUsersNames($terrariums)
        ]));
    }

    /**
     * @param Terrarium $terrarium
     * @param FormInterface $form
     * @param TerrariumService $terrariumService
     * @throws \Exception
     */
    private function createTerrarium(Terrarium $terrarium, FormInterface $form, TerrariumService $terrariumService)
    {
        $terrarium = $terrariumService->prepareTerrariumData($terrarium, $form);
        $terrarium->setCreatedTime(new \DateTime('now'));
        $terrarium->setAuth(bin2hex(random_bytes(32)));
        $this->saveTerrarium($terrarium);

        $this->addFlash('success', 'Terrarium has been added successfully!');
    }

    /**
     * @param Terrarium $terrarium
     * @param FormInterface $form
     * @param TerrariumService $terrariumService
     */
    private function updateTerrarium(Terrarium $terrarium, FormInterface $form, TerrariumService $terrariumService)
    {
        $terrarium = $terrariumService->prepareTerrariumData($terrarium, $form);
        $this->saveTerrarium($terrarium);

        $this->addFlash('success', 'Terrarium has been updated successfully!');
    }

    /**
     * @param Terrarium $terrarium
     */
    private function saveTerrarium(Terrarium $terrarium)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($terrarium);
        $entityManager->flush();
    }

    /**
     * @param FormInterface $form
     * @param Terrarium $terrarium
     * @param TerrariumService $terrariumService
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function httpRequest(FormInterface $form, Terrarium $terrarium, TerrariumService $terrariumService)
    {
        $client = HttpClient::create();
        $parameters = $terrariumService->prepareRPiData($form->getData());
        $response = $client->request( 'POST', $form->get('Url')->getData() . '/send/settings', [
                'body' => [
                    'auth' => $terrarium->getAuth(),
                    'temp_low'  => $parameters['temp_low'],
                    'temp_high'  => $parameters['temp_high'],
                    'humi_low'  => $parameters['humi_low'],
                    'humi_high'  => $parameters['humi_high'],
                    'time_light_start'  => $parameters['time_light_start'],
                    'time_light_end'  => $parameters['time_light_end']
                ],
            ]
        );

        return $response->getContent();
    }
}