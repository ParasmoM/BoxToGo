<?php

namespace App\Form\Admin;


use App\Model\SearchDataAdmin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewAdminFormType extends AbstractType
{
    public function __construct()
    {
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
            ->add('email')
            ->add('submit', SubmitType::class, [
                'label' => 'Add',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}