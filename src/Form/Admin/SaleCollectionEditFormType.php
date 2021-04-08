<?php

namespace App\Form\Admin;

use App\Entity\SaleCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SaleCollectionEditFormType extends AbstractType
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
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('validUntil', DateTimeType::class, [
                'label' => 'Valid until',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('isHidden', CheckboxType::class, [
                'label' => 'Is hidden',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
            ])
            ->add('isDeleted', CheckboxType::class, [
                'label' => 'Is deleted',
                'required' => false,
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SaleCollection::class,
        ]);
    }
}
