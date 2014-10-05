<?php

namespace JamesMannion\ForumBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JamesMannion\ForumBundle\Constants\Label;
use JamesMannion\ForumBundle\Constants\Validation as Validation;

class UserUpdateForm extends AbstractType
{

    private $name = 'profileForm';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'file',
                'file',
                array(
                    'label' => Label::PROFILE_AVATAR,
                    'data_class'        => 'JamesMannion\ForumBundle\Entity\Avatar',
                    'csrf_protection'   => true,
                    'csrf_field_name'   => '_token',
                    'intention'         => 'file'
                )
            );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JamesMannion\ForumBundle\Entity\Avatar'
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
