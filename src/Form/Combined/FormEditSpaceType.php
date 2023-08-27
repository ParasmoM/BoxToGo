<?php

namespace App\Form\Combined;

use App\Form\PhotoFormType;
use App\Form\AdresseFormType;
use App\Form\ContentFormType;
use App\DTO\FormEditSpaceModel;
use App\Form\EquipmentFormType;
use App\Form\PriceAndCategFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FormEditSpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', ContentFormType::class)
            ->add('space', PriceAndCategFormType::class)
            ->add('adresse', AdresseFormType::class)
            ->add('equipment', EquipmentFormType::class)
            ->add('galleries', PhotoFormType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormEditSpaceModel::class,
        ]);
    }
}