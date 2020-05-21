<?php

namespace App\Controller;

use App\Entity\User;
use Exception;
use App\Form\UserEditForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;

/**
 * @IsGranted("ROLE_ADMIN", message="Tik prižiūrinčios įmonės teises turinti paskyra gali pasiekti ši puslapį")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/vartotojai", name="app_users")
     * @param UserService $userService
     * @return Response
     */
    public function customers(UserService $userService): Response
    {
        $users = $userService->getUsers($this->getUser()->getId());
        return $this->render('customer/customers.html.twig', ['users' => $users ]);
    }

    /**
     * @Route("/vartotojai/sukurti", name="create_users")
     * @return Response
     */
    public function create(): Response
    {
        return $this->render('customer/register.html.twig');
    }

    /**
     * @Route("/vartotojai/redaguoti/{id}", name="edit_user")
     * @param Request $request
     * @param int $id
     * @param UserService $userService
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, int $id, UserService $userService): Response
    {
        $user = $userService->getUser($id);
        $form = $this->createForm(UserEditForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateUser($user);
        }

        return $this->render('customer/edit.html.twig', [
            'userForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/vartotojai/trinti/{id}", name="delete_user")
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

        return new JsonResponse(array('success' => 'Vartotojas "' . $user->getName() . '" buvo sėkmingai pašalintas!'));
    }

    /**
     * @Route("/users/table", name="users_table", options={"expose" = true})
     * @param Request $request
     * @param UserService $userService
     * @return Response
     */
    public function usersTable(Request $request, UserService $userService)
    {
        $users = $userService->getUsers($this->getUser()->getId());

        return new Response($this->renderView('table/customer-table.html.twig', [
                'users' => $users,
            ]));
    }

    /**
     * @param $user
     * @throws Exception
     */
    private function updateUser(User $user): void
    {
        $user->setUpdateDate(new \DateTime('now'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('success', 'Vartotojo paskyra buvo sėkmingai atnaujinta!');
    }
}