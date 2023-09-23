<?php

namespace App\Form;

use App\Entity\SpaceAmenities;
use App\Entity\SpaceAmenityLinks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AmenityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amenities', EntityType::class, [
                'class' => SpaceAmenities::class,
                'choice_label' => 'nameFr', 
                'multiple' => true,
                'expanded' => true, // pour le rendre sous forme de checkboxes
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Professionnel' => 'Professionnel',
                    'Particulier' => 'Particulier',
                ],
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpaceAmenityLinks::class,
        ]);
    }
}
