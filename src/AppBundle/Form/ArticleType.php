<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'label' => 'Title', //TODO: need to find a way to translate it automatically
                'required' => false, //Display errors against symfony constraints
            ])
            ->add('authorEmail', 'email', [
                'label' => 'Your mail',
            ])
            ->add('content', 'textarea', [
                'label' => 'Content',
                'required' => false,
            ])
            ->add('create', 'submit', [
                'label' => 'Create'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Jfacchini\Bundle\BlogBundle\Entity\Article',
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'blog_article';
    }
}