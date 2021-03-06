<?php

namespace AppBundle\Form;

use AppBundle\Entity\Article;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleAddChangeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label'    => 'name',))
            ->add('author', TextType::class, array(
                'label'    => 'author',))
            ->add('annotation', TextareaType::class, array(
                'label'    => 'annotation',))
            ->add('text', TextareaType::class, array(
                'label'    => 'text',))
            ->add('category', EntityType::class, array(
                'label'    => 'category',
                'class' => 'AppBundle:Category',
                'choice_label' => 'name',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Article::class,
        ));
    }
}