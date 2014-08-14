<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 04/08/14
 * Time: 22:17
 */

namespace JamesMannion\ForumBundle\Form\User;

use Symfony\Component\Form\FormBuilderInterface;
use JamesMannion\ForumBundle\Constants\Label;
use JamesMannion\ForumBundle\Constants\Button;
use JamesMannion\ForumBundle\Constants\Validation;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;

class UserCreateForm extends AbstractType {

    private $name = 'userCreateForm';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            'text',
            array(
                'mapped'        => true,
                'required'      => true,
                'label'         => Label::REGISTRATION_USERNAME,
                'max_length'    => 100,
            )
        )

        ->add(
            'email',
            'repeated',
            array(
                'type'              =>  'email',
                'mapped'            =>  true,
                'required'          =>  true,
                'max_length'        =>  100,
                'invalid_message'   => Validation::REGISTRATION_EMAIL_MATCH,
                'first_options'     => array(
                    'label'             => Label::REGISTRATION_EMAIL,
                ),
                'second_options' => array(
                    'label' => Label::REGISTRATION_REPEAT_EMAIL),
            )
        )

        ->add(
            'password',
            'repeated',
            array(
                'mapped'            =>  true,
                'type'              => 'password',
                'required'          =>  true,
                'max_length'        =>  100,
                'invalid_message'   => Validation::REGISTRATION_PASSWORD_MATCH,
                'first_options'     => array('label' => Label::REGISTRATION_PASSWORD),
                'second_options' => array('label' => Label::REGISTRATION_REPEAT_PASSWORD)
            )
        )

        ->add(
            'memorableQuestion',
            'entity',
            array(
                'mapped'        =>  true,
                'required'      =>  true,
                'label'         =>  Label::REGISTRATION_MEMORABLE_QUESTION,
                'class'         =>  'JamesMannionForumBundle:MemorableQuestion',
                'query_builder' =>
                    function(EntityRepository $er) {
                        return $er->createQueryBuilder('q')
                            ->orderBy('q.question', 'ASC');
                    }
            )
        )

        ->add(
            'memorableAnswer',
            'text',
            array(
                'mapped'        =>  true,
                'required'      =>  true,
                'label'         =>  Label::REGISTRATION_MEMORABLE_ANSWER,
                'max_length'    =>  100,
            )
        )

        ->add(
            'save',
            'submit',
            array(
                'label' =>  Button::REGISTRATION_SUBMIT
            )

        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
} 