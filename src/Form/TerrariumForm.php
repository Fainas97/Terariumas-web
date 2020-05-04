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
use Symfony\Component\Validator\Constraints\Regex;

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
            ->add('Temperature_range', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter terrarium temperature range',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Temperature range should contain at least {{ limit }} characters',
                        'max' => 5,
                        'maxMessage' => 'Temperature range should not contain more than {{ limit }} characters',
                    ]),
                    new Regex([
                        'pattern' => '/[0-9]{1,2}[:][0-9]{1,2}$/',
                        'message' => 'Format examples - XX:YY or XX:Y or X:Y'
                    ])
                ]
            ])
            ->add('Humidity_range', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter terrarium humidity range',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Humidity range should contain at least {{ limit }} characters',
                        'max' => 5,
                        'maxMessage' => 'Humidity range should not contain more than {{ limit }} characters',
                    ]),
                    new Regex([
                        'pattern' => '/[0-9]{1,2}[:][0-9]{1,2}$/',
                        'message' => 'Format examples - XX:YY or XX:Y or X:Y'
                    ])
                ]
            ])
            ->add('Lighting_schedule', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter terrarium lighting schedule',
                    ]),
                    new Length([
                        'min' => 17,
                        'minMessage' => 'Lighting schedule should contain at least {{ limit }} characters',
                        'max' => 17,
                        'maxMessage' => 'Lighting schedule should not contain more than {{ limit }} characters',
                    ]),
                    new Regex([
                        'pattern' => '/[0-9]{2}[:][0-9]{2}[:][0-9]{2}[-][0-9]{2}[:][0-9]{2}[:][0-9]{2}$/',
                        'message' => 'Format XX:XX:XX-YY:YY:YY'
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
            ])
            ->add('Url', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter terrarium url',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Url should contain at least {{ limit }} characters',
                        'max' => 64,
                        'maxMessage' => 'Url should not contain more than {{ limit }} characters',
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