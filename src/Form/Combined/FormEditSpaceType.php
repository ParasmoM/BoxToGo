<?php

namespace App\Form\Combined;

use App\Form\PhotoFormType;
use App\Form\AdresseFormType;
use App\Form\AmenityFormType;
use App\Form\ContentFormType;
use App\DTO\FormEditSpaceModel;
use App\Form\PriceAndCategFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormEditSpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', ContentFormType::class)
            ->add('space', PriceAndCategFormType::class)
            ->add('adresse', AdresseFormType::class)
            ->add('amenity', AmenityFormType::class)
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