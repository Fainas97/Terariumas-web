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
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Vartotojo vardas turi turėti daugiau nei {{ limit }} simbolius',
                        'max' => 70,
                        'maxMessage' => 'Vartotojo vardas turi turėti ne daugiau nei {{ limit }} simbolius',
                    ])
                ],
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
            ])
            ->add('Admin', ChoiceType::class, [
                'choices' => [
                    'Klientas' => 0,
                    'Prižiūrinti įmonė' => 1,
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