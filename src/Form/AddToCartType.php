<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class AddToCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('size', ChoiceType::class, [
                'choices' => [
                    'XS' => 'XS',
                    'S'  => 'S',
                    'M'  => 'M',
                    'L'  => 'L',
                    'XL' => 'XL',
                ],
                'label' => 'Taille'
            ]);
    }
}