<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories', CollectionType::class, [
                'entry_type' => CategoryType::class,
                'entry_options' => ['label' => false],
                'attr' => array(
                    'readonly' => true,
                ),
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('save', SubmitType::class,[
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//            'data_class' => Product::class,
//        ]);
//    }
}