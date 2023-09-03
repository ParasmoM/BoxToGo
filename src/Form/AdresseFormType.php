<?php

namespace App\Form;

use App\Entity\Adresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdresseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', TextType::class, [
                'data' => 'Belgique',
            ])
            ->add('city')
            ->add('streetNumber')
            ->add('street')
            ->add('postalCode')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresses::class,
        ]);
    }
}
