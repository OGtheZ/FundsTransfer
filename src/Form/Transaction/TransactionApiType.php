<?php

namespace App\Form\Transaction;

use App\Entity\Account;
use App\Entity\Currency;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class TransactionApiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Positive(),
                ]
            ])
            ->add('currency', EntityType::class, [
                'class' => Currency::class
            ])
            ->add('accountFrom', EntityType::class, [
                'class' => Account::class
            ])
            ->add('accountTo', EntityType::class, [
                'class' => Account::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
