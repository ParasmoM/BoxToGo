<?php

namespace App\Form;

use App\Model\SearchBarHome;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SearchBarHomeFormType extends AbstractType {
    public function __construct()
    {
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
            ->setMethod('GET') 
            ->add('dateStart', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'required' => false, 
                'constraints' => [
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date ne peut pas être dans le passé.',
                    ])
                ]
            ])
            ->add('dateEnd', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'required' => false,  
                'constraints' => [
                    new Assert\Callback(function($dateEnd, ExecutionContextInterface $context) {
                        $form = $context->getRoot();
                        $dateStart = $form['dateStart']->getData();
                        
                        // Si dateEnd est rempli, dateStart doit également être rempli
                        if ($dateEnd !== null && $dateStart === null) {
                            $context->buildViolation("Vous devez également définir une date de début.")
                                    ->atPath('dateStart')
                                    ->addViolation();
                        }
                    }),
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date ne peut pas être dans le passé.',
                    ])
                ],
            ])
            ->add('search')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchBarHome::class,
        ]);
    }
}