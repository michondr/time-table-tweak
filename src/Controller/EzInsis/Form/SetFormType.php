<?php

namespace App\Controller\EzInsis\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'name',
                    'required' => null,
                ]
            )
            ->add(
                'study_period',
                TextType::class,
                [
                    'label' => 'study_period',
                    'required' => null,
                    'attr' => ['placeholder' => 'format for exact match: ie. SS 2018/2019'],
                ]
            )
            ->add(
                'department',
                TextType::class,
                [
                    'label' => 'department',
                    'required' => null,
                    'attr' => ['placeholder' => 'format for exact match: FIS/FPH/FMV/FFU/CESP/NF/OZS/U3V'],
                ]
            )
            ->add(
                'study_form',
                TextType::class,
                [
                    'label' => 'study_form',
                    'required' => null,
                    'attr' => ['placeholder' => 'format for exact match: full-time/distance/combined'],
                ]
            )
            ->add(
                'beginning',
                TextType::class,
                [
                    'label' => 'beginning',
                    'required' => null,
                    'attr' => ['placeholder' => 'format for exact match: Y-m-d'],
                ]
            )
            ->add(
                'end',
                TextType::class,
                [
                    'label' => 'end',
                    'required' => null,
                    'attr' => ['placeholder' => 'format for exact match: Y-m-d'],
                ]
            )
            ->add(
                'lang',
                ChoiceType::class,
                [
                    'label' => 'lang',
                    'required' => null,
                    'choices' => ['EN' => 'en', 'CS' => 'cz'],
                    'placeholder' => false,
                ]
            )
            ->add(
                'exact_match',
                ChoiceType::class,
                [
                    'label' => 'exact_match',
                    'required' => null,
                    'choices' => ['true' => '1', 'false' => '0'],
                    'placeholder' => false,
                ]
            )->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Submit',
                    'attr' => ['class' => 'btn-primary'],
                ]
            );
    }

}