<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom d\'utilisateur :',
                'attr' => [
                    'placeholder' => 'Votre nom complet',
                    'class' => 'form-input'
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail :',
                'attr' => [
                    'placeholder' => 'Votre email',
                    'class' => 'form-input'
                ],
            ])
            ->add('deliveryAddress', TextType::class, [
                'label' => 'Adresse de livraison :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Adresse de livraison (optionnel)',
                    'class' => 'form-input'
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mot de passe :',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 4096
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Confirmer le mot de passe :',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez confirmer votre mot de passe'
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}


