<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType; 
use App\Entity\Author; 

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
            ])
            ->add('nbrPage', IntegerType::class, [
                'label' => 'Number of Pages',
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'name', // Affiche le nom de l'auteur
                'multiple' => false, // Désactive la sélection multiple pour un ManyToOne
                'expanded' => false, // Affiche comme liste déroulante
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'choices' => [
                    'Science-Fiction' => 'science_fiction',
                    'Mystery' => 'mystery',
                    'Autobiography' => 'autobiography',
                    'Romance' => 'romance',
                ],
                'placeholder' => 'Choose a category',
                'required' => true,
            ])
            ->add('publicationDate', DateType::class, [
                'widget' => 'choice',
                'years' => range(1900, date('Y') + 10), // Inclut les années à partir de 1900
                'months' => range(1, 12),
                'days' => range(1, 31),
                'placeholder' => [
                    'year' => 'Year',
                    'month' => 'Month',
                    'day' => 'Day',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livre::class, // L'entité associée
        ]);
    }
}
