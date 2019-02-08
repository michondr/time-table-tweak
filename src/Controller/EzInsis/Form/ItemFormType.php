<?php

namespace App\Controller\EzInsis\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'day',
                TextType::class,
                [
                    'label' => 'day',
                    'required' => null,
                    'attr' => ['placeholder' => 'format for exact match: mon/tue/wed/thu/fri'],
                ]
            )
            ->add(
                'time_from',
                TextType::class,
                [
                    'label' => 'time_from',
                    'required' => null,
                    'attr' => ['placeholder' => 'format for exact match: HH:ii'],
                ]
            )
            ->add(
                'time_until',
                TextType::class,
                [
                    'label' => 'time_until',
                    'required' => null,
                    'attr' => ['placeholder' => 'format for exact match: HH:ii'],
                ]
            )
            ->add(
                'entry',
                TextType::class,
                [
                    'label' => 'entry',
                    'required' => null,
                    'attr' => ['placeholder' => 'format for exact match: seminar/lecture'],
                ]
            )
            ->add(
                'teacher_id',
                TextType::class,
                [
                    'label' => 'teacher id',
                    'required' => null,
                ]
            )
            ->add(
                'teacher_name',
                TextType::class,
                [
                    'label' => 'teacher name',
                    'required' => null,
                ]
            )
            ->add(
                'subject_id',
                TextType::class,
                [
                    'label' => 'subject id',
                    'required' => null,
                ]
            )
            ->add(
                'subject_name',
                TextType::class,
                [
                    'label' => 'subject name',
                    'required' => null,
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
