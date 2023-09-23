<?php

namespace App\Form\Combined;

use App\Form\PhotoFormType;
use App\Form\AccountFormType;
use App\Form\ContentFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FormSettingsAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo', PhotoFormType::class, ['required' => false])
            ->add('description', ContentFormType::class, ['required' => false])
            ->add('account', AccountFormType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([FormSettingsAccountModel::class]);
    }
}