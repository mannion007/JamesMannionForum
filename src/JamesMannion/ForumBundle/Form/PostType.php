<?php

namespace JamesMannion\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JamesMannion\ForumBundle\Constants\Label;

class PostType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'body',
                'text',
                array(
                    'label' => Label::POST_BODY
                )
            )
            ->add(
                'author',
                'entity',
                array(
                    'class'     => 'JamesMannionForumBundle:User',
                    'property'  => 'username',
                    'label'     => Label::POST_AUTHOR
                )
            )
            ->add(
                'thread',
                'entity',
                array(
                    'class'     => 'JamesMannionForumBundle:Thread',
                    'property'  => 'title',
                    'label'     => Label::POST_THREAD
                )
            )

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JamesMannion\ForumBundle\Entity\Post'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jamesmannion_forumbundle_post';
    }
}
