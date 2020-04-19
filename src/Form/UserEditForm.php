<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter name',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Name should contain at least {{ limit }} characters',
                        'max' => 70,
                        'maxMessage' => 'Name should not contain more than {{ limit }} characters',
                    ])
                ],
            ])
            ->add('Email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter email',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Email should contain at least {{ limit }} characters',
                        'max' => 100,
                        'maxMessage' => 'Email should not contain more than {{ limit }} characters',
                    ])
                ],
            ])
            ->add('Admin', ChoiceType::class, [
                'choices' => [
                    'Client' => 0,
                    'Admin' => 1,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}