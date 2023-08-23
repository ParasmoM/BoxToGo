<?php

namespace App\Form;

use App\DTO\FormAddNewSpaceModel;
use App\Form\ImageFormType;
use App\Form\SpaceFormType;
use App\Form\AdresseFormType;
use App\Form\ContentsFormType;
use App\Form\EquipmentFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormAddNewSpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('space', SpaceFormType::class)
            ->add('adresse', AdresseFormType::class)
            ->add('equipment', EquipmentFormType::class)
            ->add('image', ImageFormType::class)
            ->add('content', ContentsFormType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([FormAddNewSpaceModel::class]);
    }
}