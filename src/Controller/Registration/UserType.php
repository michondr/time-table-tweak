<?php

namespace App\Controller\Registration;

use App\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email address',
                    'attr' => [
                        'class' => 'form-control',
                        'aria-describedby' => 'emailHelp',
                        'placeholder' => 'Enter email',
                    ],
                ]
            )
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'Username',
                    'attr' => [
                        'class' => 'form-control',
                        'aria-describedby' => 'emailHelp',
                        'placeholder' => 'Username',
                    ],
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Password',
                        'attr' => [
                            'class' => 'form-control',
                            'placeholder' => 'Password',
                        ],
                    ],
                    'second_options' => [
                        'label' => 'Repeat Password',
                        'attr' => [
                            'class' => 'form-control',
                            'placeholder' => 'Password',
                        ],
                    ],
                ]
            )->add(
                'submit',
                SubmitType::class,
                [
                    'attr' =>
                        ['class' => 'btn btn-primary'],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }
}