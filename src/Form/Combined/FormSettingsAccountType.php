<?php

namespace App\Form\Combined;

use App\Form\PhotoFormType;
use App\Form\AccountFormType;
use App\Form\ContentFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormSettingsAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo', PhotoFormType::class)
            ->add('description', ContentFormType::class)
            ->add('account', AccountFormType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([FormSettingsAccountModel::class]);
    }
}