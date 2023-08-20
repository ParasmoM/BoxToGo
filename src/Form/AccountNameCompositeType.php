<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AccountNameCompositeType  extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('familyName', TextType::class, [
                'label' => 'Family Name',
                'attr' => ['class' => 'custom-class'],
            ])
            ->add('givenName', TextType::class, [
                'label' => 'Given Name',
                'attr' => ['class' => 'custom-class'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'label' => false,
            'mapped' => false,
        ]);
    }
}