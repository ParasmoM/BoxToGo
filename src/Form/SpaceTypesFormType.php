<?php

namespace App\Form;

use App\Entity\SpaceTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

class SpaceTypesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_fr', TextType::class, [
                'invalid_message' => 'This field can not be empty.',
            ])
            ->add('name_en', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field can not be empty.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Add',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpaceTypes::class,
        ]);
    }
}
