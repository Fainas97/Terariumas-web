<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("ROLE_ADMIN", message="Tik prižiūrinčios įmonės teises turinti paskyra gali pasiekti ši puslapį")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/registruoti", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('Password')->getData()
                )
            );

            $user->setCreatedDate(new \DateTime('now'));
            $user->setUpdateDate(new \DateTime('now'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email TODO
            $this->addFlash('success', 'Vartotojo paskyra sėkmingai sukurta!');

            return $this->redirectToRoute('app_users');
        }

        return $this->render('customer/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
