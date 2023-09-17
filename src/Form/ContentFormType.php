<?php

namespace App\Form;

use App\Entity\Contents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titleFr', TextType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => 'La valeur doit être une chaîne de caractères.',
                    ]),
                ],
            ])
            ->add('titleEn', TextType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => 'La valeur doit être une chaîne de caractères.',
                    ]),
                ],
            ])
            ->add('titleNl', TextType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => 'La valeur doit être une chaîne de caractères.',
                    ]),
                ],
            ])
            ->add('descriptionFr', TextareaType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => 'La valeur doit être une chaîne de caractères.',
                    ]),
                ],
                'required' => false,  
            ])
            ->add('descriptionEn', TextareaType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => 'La valeur doit être une chaîne de caractères.',
                    ]),
                ],
                'required' => false,  
            ])
            ->add('descriptionNl', TextareaType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => 'La valeur doit être une chaîne de caractères.',
                    ]),
                ],
                'required' => false,  
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contents::class,
        ]);
    }
}
