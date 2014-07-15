<?php

namespace JamesMannion\ForumBundle\Form\Type;

use JamesMannion\ForumBundle\Constants\Config;
use JamesMannion\ForumBundle\Constants\Label;
use JamesMannion\ForumBundle\Helper\DateHelper;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add(
            'forename',
            'text',
            array(
                'label' => Label::REGISTRATION_FORENAME
            )
        );

        $builder->add(
            'surname',
            'text',
            array(
                'label' => Label::REGISTRATION_SURNAME
            )
        );

        $builder->add(
            'dob',
            'birthday',
            array(
                'label' => Label::REGISTRATION_DOB,
                'years' => DateHelper::listYearsInRange(
                        Config::MAXIMUM_AGE, Config::MINIMUM_AGE)
            )
        );

    }

    public function getName()
    {
        return 'forum_user_registration';
    }
}