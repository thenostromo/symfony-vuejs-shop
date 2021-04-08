<?php

namespace App\Form\Admin;

use App\DataProvider\OrderDataProvider;
use App\Entity\Order;
use App\Entity\PromoCode;
use App\Entity\User;
use App\Repository\PromoCodeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderEditFormType extends AbstractType
{
    /**
     * @var PromoCodeRepository
     */
    private $promoCodeRepository;

    /**
     * @param PromoCodeRepository $promoCodeRepository
     */
    public function __construct(PromoCodeRepository $promoCodeRepository)
    {
        $this->promoCodeRepository = $promoCodeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('owner', EntityType::class, [
                'label' => 'Owner',
                'class' => User::class,
                'required' => false,
                'choice_label' => 'email',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'required' => false,
                'choices' => array_flip(OrderDataProvider::getStatusList()),
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('promoCode', EntityType::class, [
                'label' => 'Promo code',
                'class' => PromoCode::class,
                'required' => false,
                'choice_label' => function (PromoCode $promoCode) {
                    return sprintf(
                        '#%s %s (-%s%%): %s',
                        $promoCode->getId(),
                        $promoCode->getTitle(),
                        $promoCode->getDiscount(),
                        $promoCode->getValue()
                    );
                },
                'choices' => $this->promoCodeRepository->getActiveList(),
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
