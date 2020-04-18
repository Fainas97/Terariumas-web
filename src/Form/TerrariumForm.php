<?php

namespace App\Form;

use App\Entity\Terrarium;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TerrariumForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Users', EntityType::class,
                [
                    'class' => User::class,
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter terrarium name',
                        ]),
                    ]
                ])
            ->add('Name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter terrarium name',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Name should contain at least {{ limit }} characters',
                        'max' => 70,
                        'maxMessage' => 'Name should not contain more than {{ limit }} characters',
                    ])
                ]
            ])
            ->add('Settings', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter terrarium settings',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Name should contain at least {{ limit }} characters',
                        'max' => 70,
                        'maxMessage' => 'Name should not contain more than {{ limit }} characters',
                    ])
                ]
            ])
            ->add('Address', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter terrarium address',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Name should contain at least {{ limit }} characters',
                        'max' => 100,
                        'maxMessage' => 'Name should not contain more than {{ limit }} characters',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Terrarium::class,
        ]);
    }
}