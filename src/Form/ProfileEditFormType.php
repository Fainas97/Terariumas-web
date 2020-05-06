<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;

class ProfileEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Vartotojo vardas turi turėti daugiau nei {{ limit }} simbolius',
                        'max' => 70,
                        'maxMessage' => 'Vartotojo vardas turi turėti ne daugiau nei {{ limit }} simbolius',
                    ])
                ],
                'required' => true,
            ])
            ->add('Email', EmailType::class, [
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'El. paštas turi turėti daugiau nei {{ limit }} simbolius',
                        'max' => 100,
                        'maxMessage' => 'El. paštas turi turėti ne daugiau nei {{ limit }} simbolius',
                    ])
                ],
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Slaptažodžių laukai turi sutapti',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat password'],
                'constraints' => new UserPassword(array('message' => 'Slaptažodis neteisingas')),
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
