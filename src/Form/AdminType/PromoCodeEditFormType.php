<?php

namespace App\Form\AdminType;

use App\Entity\PromoCode;
use App\Form\DTO\PromoCodeEditModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromoCodeEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('uses', IntegerType::class, [
                'label' => 'Uses',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('value', TextType::class, [
                'label' => 'Promo code',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('discount', IntegerType::class, [
                'label' => 'Discount (%)',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('validUntil', DateTimeType::class, [
                'label' => 'Valid until',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => true,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Is active',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PromoCodeEditModel::class,
        ]);
    }
}
