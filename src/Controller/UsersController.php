<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="app_users")
     * @param UserService $userService
     * @return Response
     */
    public function customers(UserService $userService): Response
    {
        $user = $this->getUser();
        if ($user->getAdmin()) {
            $users = $userService->getUsers($user->getId());
            return $this->render('dashboard/customers.html.twig', ['users' => $users ]);
        }
    }

    /**
     * @Route("/users/create", name="create_users")
     * @return Response
     */
    public function create(): Response
    {
        return $this->render('registration/register.html.twig');
    }

    /**
     * @Route("/user/edit/{id}", name="edit_user")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {

    }

    /**
     * @Route("/user/delete/{id}", name="delete_user")
     * @param int $id
     * @param UserService $userService
     * @return Response
     */
    public function delete(int $id, UserService $userService): Response
    {
        $user = $userService->getUser($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(array('success' => 'User has been removed successfully!'));
    }
}