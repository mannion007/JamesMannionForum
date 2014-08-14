<?php

namespace JamesMannion\ForumBundle\Form\Authentication;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JamesMannion\ForumBundle\Constants\Label;

class AuthenticationLoginForm extends AbstractType
{
    private $name = 'authenticationLoginForm';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            'text',
            array(
                'required' => true,
                'attr' => array(
                    'placeholder' => Label::AUTHENTICATION_USER_USERNAME
                )
            )
        )
        ->add(
            'password',
            'password',
            array(
                'required' => true,
                'attr' => array(
                    'placeholder' => Label::AUTHENTICATION_USER_PASSWORD
                )
            )
        );
    }

    public function getName(){
        return $this->name;
    }
}