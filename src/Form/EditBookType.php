<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add ('published',CheckboxType::class)
            ->add('publicationdate',DateType::class)
            ->add('category',ChoiceType::class,[
                'choices' => [
                    'Mystery' => 'Mystery',
                    'Science-Fiction' => 'Science_Fiction',
                    'Autobiography' => 'Autobiography',
                ]
            ])
            ->add('Author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => function (Author $author) {
                    return sprintf('%s (%d)', $author->getUsername(), $author->getId());
                }
            ])
            ->add('add',SubmitType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
