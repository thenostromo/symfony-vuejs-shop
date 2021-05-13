<?php

namespace App\Form\AdminType;

use App\Entity\DataStorage\UserDataStorage;
use App\Entity\User;
use App\Form\DTO\UserEditModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', TextType::class, [
                'label' => 'New password',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('fullName', TextType::class, [
                'label' => 'Full name',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Zip code',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'required' => false,
                'choices' => array_flip(UserDataStorage::getUserRolesChoices()),
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('isVerified', CheckboxType::class, [
                'label' => 'Is verified',
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
            'data_class' => UserEditModel::class,
        ]);
    }
}
