<?php

namespace App\Form;

use App\DTO\DynamicCompositeModel;
use App\Form\PriceAndCategFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CombinedFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', ContentFormType::class)
            ->add('space', PriceAndCategFormType::class)
            ->add('adresse', AdresseFormType::class)
            ->add('equipment', EquipmentFormType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DynamicCompositeModel::class,
        ]);
    }
}