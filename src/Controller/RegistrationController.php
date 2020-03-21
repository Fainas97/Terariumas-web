<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use phpDocumentor\Reflection\DocBlock\Tags\Formatter\AlignFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,
                             ValidatorInterface $validator): Response
    {
        $userRequest = $request->request->all();
        $lastEmail = $request->request->get('email');
        $lastName = $request->request->get('name');
        $errors = [];

        if ($request->isMethod('post')) {

            $user = new User();
            $user->setName($lastName);
            $user->setEmail($lastEmail);
            $user->setAdmin((int) $userRequest['admin']);
            $user->setCreatedDate(new \DateTime('now'));
            $user->setUpdateDate(new \DateTime('now'));
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $userRequest['password']
                )
            );

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->render('registration/register.html.twig',
                    ['last_name' => $lastName, 'last_email' => $lastEmail, 'errors' => $errors]);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig',
            ['last_name' => $lastName, 'last_email' => $lastEmail, 'errors' => $errors]);
    }
}
