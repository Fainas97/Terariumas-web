<?php

namespace App\Controller;

use App\Entity\User;
use Exception;
use App\Service\UserService;
use App\Form\ProfileEditFormType;
use App\Form\ProfilePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * ProfileController constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/profile", name="app_profile")
     * @param Request $request
     * @param UserService $userService
     * @return Response
     * @throws Exception
     */
    public function profile(Request $request, UserService $userService): Response
    {
        $user = $userService->getUser($this->getUser()->getId());
        $formPass = $this->createForm(ProfilePasswordFormType::class, $user);
        $formPass->handleRequest($request);

        $formEdit = $this->createForm(ProfileEditFormType::class, $user);
        $formEdit->handleRequest($request);

        if ($formPass->isSubmitted() && $formPass->isValid()) {
            $this->updatePassword($user, $formPass->get('newPassword')->getData());
        }
        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            $this->updateProfile($user);
        }

        // do anything else you need here, like send an email
        return $this->render('profile/profile.html.twig', [
            'profileForm' => $formPass->createView(),
            'profileEditForm' => $formEdit->createView(),
            'user' => $user
        ]);
    }

    /**
     * @param $user
     * @param $plainPassword
     * @throws Exception
     */
    private function updatePassword(User $user, string $plainPassword): void
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $plainPassword));
        $user->setUpdateDate(new \DateTime('now'));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->addFlash('success', 'Your password has been successfully updated!');
    }

    /**
     * @param $user
     * @throws Exception
     */
    private function updateProfile(User $user): void
    {
        $user->setUpdateDate(new \DateTime('now'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('success', 'Your profile has been successfully updated!');
    }
}