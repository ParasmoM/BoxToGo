<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AppearanceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('appearance', ChoiceType::class, [
            'choices'  => [
                '' => '',
                'Thème claire' => 'light',
                'Thème sombre' => 'dark',
            ],
            // 'expanded' => true, // Render as radio buttons
            // 'multiple' => false, // Only one can be selected
            // 'attr' => ['class' => 'custom-class'],
        ])  
            ->add('language', ChoiceType::class, [
                'choices'  => [
                    '' => ''
,                    'Anglais' => 'EN',
                    'Français' => 'FR',
                    'Néerlandais' => 'NL',
                ],
                // 'expanded' => true, // Render as radio buttons
                // 'multiple' => false, // Only one can be selected
                // 'attr' => ['class' => 'custom-class'],
            ])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
