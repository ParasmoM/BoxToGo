<?php

namespace App\Form;

use App\Entity\SpaceTranslations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SpaceTranslationsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('language', ChoiceType::class, [
                'choices'  => [
                    '' => '',
                    'Anglais' => 'EN',
                    'Français' => 'FR',
                    'Néerlandais' => 'NL',
                ],
                // 'placeholder' => 'Sélectionnez une langue',
            ])
            ->add('title')
            ->add('Description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Saisissez votre texte ici...',
                    'style' => 'height: 150px; resize: none;',
                ],
            ])
            // ->add('space')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpaceTranslations::class,
        ]);
    }
}
