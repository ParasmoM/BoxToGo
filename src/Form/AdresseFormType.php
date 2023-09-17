<?php

namespace App\Form;

use App\Entity\Addresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AdresseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', TextType::class, [
                'data' => 'Belgique',
                'disabled' => true,
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => 'La valeur doit être une chaîne de caractères.',
                    ]),
                ],
            ])
            ->add('streetNumber')
            ->add('street', TextType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => 'La valeur doit être une chaîne de caractères.',
                    ]),
                ],
            ])
            ->add('postalCode', IntegerType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'integer',
                        'message' => 'Veuillez entrer un nombre entier.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Addresses::class,
        ]);
    }
}
