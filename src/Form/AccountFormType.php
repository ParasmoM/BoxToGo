<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AccountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('familyName')
            ->add('givenName')
            ->add('email', null, [
                'disabled' => true,
            ])
            ->add('phoneNumber')
            ->add('birthDate', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text', 
                'required' => false, 
            ])
            ->add('language', ChoiceType::class, [
                'choices'  => [
                    'Anglais' => 'EN',
                    'Français' => 'FR',
                    'Néerlandais' => 'NL',
                ],
                'expanded' => true, 
                'multiple' => false, 
                // 'required' => false, 
            ])
            ->add('status',  ChoiceType::class, [
                'choices'  => [
                    // 'Aucun' => null,
                    'Professionnel' => 'Professionnel',
                    'Particulier' => 'Particulier',
                    // 'required' => false, 
                ],
                'expanded' => true,
                'multiple' => false, 
                'placeholder' => 'Non spécifié',

                // 'required' => false, 
            ])
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    // 'Aucun' => null,
                    'Femme' => 'Femme',
                    'Homme' => 'Homme',
                    'Autre' => 'autre',
                ],
                'expanded' => true,
                'multiple' => false,
                'placeholder' => 'Non spécifié',

                // 'required' => false,  
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
