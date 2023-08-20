<?php

namespace App\Form;

use App\Entity\Spaces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SpaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('spaceCateg')
            ->add('itemCondition', ChoiceType::class, [
                'label' => 'État du bien',
                'choices' => [
                    '' => '',
                    'Neuf' => 'neuf',
                    'Rénové' => 'renove',
                    'À rénover' => 'a_renover',
                    'Ancien' => 'ancien'
                ]
            ])
            ->add('price')
            ->add('surface')
            ->add('entryWidth')
            ->add('entryLength')
            ->add('floorPosition', ChoiceType::class, [
                'choices' => [
                    'Rez de chaussée' => 'Rez de chaussée',
                    'Premier étage' => 'Premier étage',
                    'Deuxième étage' => 'Deuxième étage',
                ],
                'expanded' => true,
            ])
            ->add('availabilityStart', DateType::class, [
                'label' => 'Date de disponibilité',
                'widget' => 'single_text', // Ceci rendra le champ en tant qu'input de type "date"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Spaces::class,
        ]);
    }
}
