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
                            'message' => 'Terariumui turi būti priskirtas vartotojas',
                        ]),
                    ]
                ])
            ->add('Name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Terariumo pavadinimas turi turėti daugiau nei {{ limit }} simbolius',
                        'max' => 70,
                        'maxMessage' => 'Terariumo pavadinimas turi turėti ne daugiau nei {{ limit }} simbolius',
                    ])
                ]
            ])
            ->add('Temperature_range', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Temperatūros riba su histerize turi turėti daugiau nei {{ limit }} simbolius',
                        'max' => 5,
                        'maxMessage' => 'Temperatūros riba su histerize turi turėti ne daugiau nei {{ limit }} simbolius',
                    ]),
                    new Regex([
                        'pattern' => '/[0-9]{1,2}[:][0-9]{1,2}$/',
                        'message' => 'Rekalingos formato pavyzdys - XX:YY or XX:Y or X:Y'
                    ])
                ]
            ])
            ->add('Humidity_range', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Drėgmės riba su histerize turi turėti daugiau nei {{ limit }} simbolius',
                        'max' => 5,
                        'maxMessage' => 'Drėgmės riba su histerize turi turėti ne daugiau nei {{ limit }} simbolius',
                    ]),
                    new Regex([
                        'pattern' => '/[0-9]{1,2}[:][0-9]{1,2}$/',
                        'message' => 'Rekalingos formato pavyzdys - XX:YY or XX:Y or X:Y'
                    ])
                ]
            ])
            ->add('Lighting_schedule', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 17,
                        'minMessage' => 'Apšvietimo grafikas turi turėti daugiau nei {{ limit }} simbolius',
                        'max' => 17,
                        'maxMessage' => 'Apšvietimo grafikas turi turėti ne daugiau nei {{ limit }} simbolius',
                    ]),
                    new Regex([
                        'pattern' => '/[0-9]{2}[:][0-9]{2}[:][0-9]{2}[-][0-9]{2}[:][0-9]{2}[:][0-9]{2}$/',
                        'message' => 'Formatas XX:XX:XX-YY:YY:YY'
                    ])
                ]
            ])
            ->add('Address', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Adresas turi turėti daugiau nei {{ limit }} simbolius',
                        'max' => 100,
                        'maxMessage' => 'Adresas turi turėti ne daugiau nei {{ limit }} simbolius',
                    ])
                ]
            ])
            ->add('Url', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Valdiklio tinklalapio adresas turi turėti daugiau nei {{ limit }} simbolius',
                        'max' => 64,
                        'maxMessage' => 'Valdiklio tinklalapio adresas turi turėti ne daugiau nei {{ limit }} simbolius',
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