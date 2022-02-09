<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Types;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "*Nom"
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "*Description"
                ]
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('adress', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "*Adresse"
                ]
            ])
            ->add('adress2', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "*Complément d'adresse"
                ]
            ])
            ->add('postcode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "*Code postal"
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "*Ville"
                ]
            ])
            ->add('pays', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "*Pays"
                ]
            ])
            ->add('createdAt')
            // ->add('updatedAt')
            // ->add('type', EntityType::class, [
            //     'label' => "*Type :",
            //     'class' => Types::class,
            //     'multiple' => false,
            //     'expanded' => false,
            //     'mapped' => false

            // ])
            // ->add('category', EntityType::class, [
            //     'label' => "*Categorie :",
            //     'class' => Category::class,
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => false
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
