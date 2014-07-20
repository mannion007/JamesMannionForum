<?php

namespace JamesMannion\ForumBundle\Form;

use JamesMannion\ForumBundle\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JamesMannion\ForumBundle\Constants\Label;

class ThreadType extends AbstractType
{

    private $name = 'threadForm';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                'text',
                array(
                    'label' => Label::THREAD_TITLE
                )
            )
            ->add(
                'posts',
                'collection',
                array(
                    'type'      => new PostType(),
                    'allow_add' => false,
                    'data'      => array(new Post())
                )
            );

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JamesMannion\ForumBundle\Entity\Thread'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
