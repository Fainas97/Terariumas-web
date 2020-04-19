<?php

namespace App\Controller;

use App\Entity\Terrarium;
use App\Form\TerrariumForm;
use App\Service\TerrariumService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TerrariumController extends AbstractController
{
    /**
     * @Route("/terrariums", name="terrariums_show")
     * @param TerrariumService $terrariumService
     * @param UserService $userService
     * @return Response
     */
    public function terrariums(TerrariumService $terrariumService, UserService $userService): Response
    {
        $user = $this->getUser();
        if ($user->getAdmin()) {
            $terrariums = $terrariumService->getTerrariums();
            $userNames = $userService->getUsersNames($terrariums);

            return $this->render('terrarium/terrariums.html.twig', [
                'terrariums' => $terrariums,
                'userNames' => $userNames
            ]);
        }
    }

    /**
     * @Route("/terrariums/create", name="create_terrariums")
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
            $this->createTerrarium($terrarium, $form, $terrariumService);

            return $this->redirectToRoute('terrariums_show');
        }

        return $this->render('terrarium/terrarium-management.html.twig', [
            'title' => 'Add terrarium',
            'terrariumForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/terrarium/edit/{id}", name="edit_terrarium")
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
            $this->updateTerrarium($terrarium, $form, $terrariumService);

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

    private function saveTerrarium(Terrarium $terrarium)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($terrarium);
        $entityManager->flush();
    }
}