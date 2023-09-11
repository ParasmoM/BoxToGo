<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContentsFormType extends AbstractType
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
                'mapped' => false,
            ])
            ->add('title', null, [
                'mapped' => false,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Saisissez votre texte ici...',
                    'style' => 'height: 150px; resize: none;',
                ],
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
