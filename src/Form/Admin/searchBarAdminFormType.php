<?php

namespace App\Form\Admin;


use App\Model\SearchDataAdmin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class searchBarAdminFormType extends AbstractType
{
    public function __construct()
    {
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $options['categories'];
    
        // $categoriesArray = [];
        // foreach ($categories as $category) {
            
        //     $categoriesArray[$category->getNameEn()] = $category->getId(); 
        // }

        $builder 
            ->setMethod('GET') 
            ->add('reference', TextType::class, [
                'row_attr' => [
                    'class' => 'admin-features-search__item'
                ],
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Professionnel' => 'professionnel',
                    'Particulier' => 'particulier',
                ],
                'row_attr' => [
                    'class' => 'admin-features-search__item'
                ],
                'required' => false,
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $categories,
                'row_attr' => [
                    'class' => 'admin-features-search__item'
                ],
                'required' => false,
            ])
            ->add('customer', TextType::class, [
                'row_attr' => [
                    'class' => 'admin-features-search__item'
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchDataAdmin::class,
            'categories' => []
        ]);
    }
}