<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NotificationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', CheckboxType::class, [
                'label' => 'Par e-mail',
                'required' => false,
            ])
            ->add('alert', CheckboxType::class, [
                'label' => 'Par notification',
                'required' => false,
            ])
            ->add('Commentaires', CheckboxType::class, [
                'label' => 'Commentaires',
                'required' => false,
            ])
            ->add('Favoris', CheckboxType::class, [
                'label' => 'Favoris',
                'required' => false,
            ])
            ->add('Messages', CheckboxType::class, [
                'label' => 'Messages',
                'required' => false,
            ])
            // ->add('type')
            // ->add('referenceId')
            // ->add('message')
            // ->add('dateSent')
            // ->add('wasRead')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
