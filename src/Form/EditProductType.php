<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class EditProductType
 *
 * Formulaire pour éditer un produit.
 */
class EditProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Modifier le nom',
            ])
           ->add('price', TextType::class, [
                'label' => 'Modifier le prix',
            ])
            ->add('image', FileType::class, [
                'label' => 'Modifier l\'image',
                'mapped' => false, 
                'required' => false,
                'constraints' => [
                    new File([
                'maxSize' => '2M',
                'mimeTypes' => [
                'image/jpeg',
                'image/png',
                'image/webp',
            ],
                'mimeTypesMessage' => 'Veuillez uploader une image valide',
            ])
            ],
            ])
            ->add('featured', CheckboxType::class, [
                'label' => 'Mettre en avant ?',
                'required' => false,
            ])
            ->add('stockXS', IntegerType::class, [
                'label' => 'Stock XS',
            ])
            ->add('stockS', IntegerType::class, [
                'label' => 'Stock S',
            ])
            ->add('stockM', IntegerType::class, [
                'label' => 'Stock M',
            ])
            ->add('stockL', IntegerType::class, [
                'label' => 'Stock L',
            ])
            ->add('stockXL', IntegerType::class, [
                'label' => 'Stock XL',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}