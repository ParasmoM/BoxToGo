<?php

namespace App\Form\Combined;

use App\DTO\FormAddNewSpaceModel;
use App\Form\ImageFormType;
use App\Form\SpaceFormType;
use App\Form\AdresseFormType;
use App\Form\ContentsFormType;
use App\Form\AmenityFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FormAddNewSpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('space', SpaceFormType::class)
            ->add('adresse', AdresseFormType::class)
            ->add('amenity', AmenityFormType::class)
            ->add('image', ImageFormType::class)
            ->add('content', ContentsFormType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([FormAddNewSpaceModel::class]);
    }
}