<?php

namespace App\Form;

use App\Entity\Conversations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConversationsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('content')
            // ->add('createdAt')
            // ->add('sender')
            // ->add('receiver')
            // ->add('space')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conversations::class,
        ]);
    }
}
