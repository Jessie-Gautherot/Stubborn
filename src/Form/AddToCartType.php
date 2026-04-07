<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class AddToCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Taille du produit
            ->add('size', ChoiceType::class, [
                'choices' => [
                    'XS' => 'XS',
                    'S'  => 'S',
                    'M'  => 'M',
                    'L'  => 'L',
                    'XL' => 'XL',
                ],
                'label' => 'Taille',
            ])
            // Quantité
            ->add('quantity', IntegerType::class, [
                'data' => 1,
                'label' => 'Quantité',
            ])
            // Champ caché productId envoyé correctement
            ->add('productId', HiddenType::class, [
                'mapped' => false, // ne mappe pas à un objet, juste envoyé
            ]);
    }

    // Force le prefix pour que le tableau POST s'appelle add_to_cart
    public function getBlockPrefix(): string
    {
        return 'add_to_cart';
    }
}