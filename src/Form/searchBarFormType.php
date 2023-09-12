<?php

namespace App\Form;

use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class searchBarFormType extends AbstractType
{
    public function __construct()
    {
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
            ->add('search', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre recherche'
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
        ]);
    }
}