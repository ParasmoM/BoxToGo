<?php

namespace App\Form;

use App\Entity\Contents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titleFr')
            ->add('titleEn')
            ->add('titleNl')
            ->add('descriptionFr')
            ->add('descriptionEn')
            ->add('descriptionNl')
            // ->add('user')
            // ->add('space')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contents::class,
        ]);
    }
}
