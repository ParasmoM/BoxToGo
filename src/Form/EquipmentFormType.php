<?php

namespace App\Form;

use App\Entity\SpaceEquipementLink;
use App\Entity\SpaceEquipements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipmentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('quantity')
            // ->add('equipped')
            ->add('equipmentName', EntityType::class, [
                'class' => SpaceEquipements::class, // Assurez-vous de mettre le bon chemin vers votre entité 'Equipment' ici
                'choice_label' => 'name', // le champ que vous voulez afficher de votre entité (probablement le nom de l'équipement)
                'expanded' => true, // Cela transformera la liste en un ensemble de cases à cocher
                'multiple' => true, // Cela vous permettra de sélectionner plusieurs équipements
            ])
            // ->add('space')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpaceEquipementLink::class,
        ]);
    }
}
