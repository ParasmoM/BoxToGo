<?php

namespace App\Form;

use App\Entity\SpaceEquipements;
use App\Entity\SpaceEquipementLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EquipmentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('spaceEquipments', EntityType::class, [
                'class' => SpaceEquipements::class,
                'choice_label' => 'name', // Supposant que vous avez une propriété "name" dans SpaceEquipements
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
            'data_class' => SpaceEquipementLink::class,
        ]);
    }
}
