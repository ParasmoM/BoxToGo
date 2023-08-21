<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AccountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('profilePicture', FileType::class, [
                'attr' => ['class' => 'section-input'],
                'required' => false
            ])
            ->add('familyName')
            ->add('givenName')
            ->add('email', null, [
                'disabled' => true,
            ])
            ->add('phoneNumber')
            ->add('birthDate', DateType::class, [
                'label' => 'Date de disponibilité',
                'widget' => 'single_text', // Ceci rendra le champ en tant qu'input de type "date"
            ])
            ->add('language', ChoiceType::class, [
                'choices'  => [
                    'Anglais' => 'EN',
                    'Français' => 'FR',
                    'Néerlandais' => 'NL',
                ],
                'expanded' => true, // Render as radio buttons
                'multiple' => false, // Only one can be selected
                'attr' => ['class' => 'custom-class'],
            ])
            ->add('status',  ChoiceType::class, [
                'choices'  => [
                    'Professionnel' => 'professionnel',
                    'Particulier' => 'particulier',
                ],
                'expanded' => true, // Render as radio buttons
                'multiple' => false, // Only one can be selected
                'attr' => ['class' => 'custom-class'],
            ])
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Femme' => 'femme',
                    'Homme' => 'homme',
                    'Autre' => 'autre',
                ],
                'expanded' => true, // Render as radio buttons
                'multiple' => false, // Only one can be selected
                'attr' => ['class' => 'custom-class'],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
