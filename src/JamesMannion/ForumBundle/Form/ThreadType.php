<?php

namespace JamesMannion\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JamesMannion\ForumBundle\Constants\Label;

class ThreadType extends AbstractType
{
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
                'author',
                'entity',
                array(
                    'class'     => 'JamesMannionForumBundle:User',
                    'property'  => 'username',
                    'label'     => Label::THREAD_AUTHOR
                )
            )
            ->add(
                'room',
                'entity',
                array(
                    'class'     => 'JamesMannionForumBundle:Room',
                    'property'  => 'name',
                    'label'     => Label::THREAD_ROOM
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
        return 'jamesmannion_forumbundle_thread';
    }
}
