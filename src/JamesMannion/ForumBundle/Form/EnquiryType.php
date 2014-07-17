<?php

namespace JamesMannion\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JamesMannion\ForumBundle\Constants\Label;

class EnquiryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => Label::ENQUIRY_NAME
                )
            )
            ->add(
                'email',
                'email',
                array(
                    'label' => Label::ENQUIRY_EMAIL
                )
            )
            ->add(
                'telephone',
                'number',
                array(
                    'label' => Label::ENQUIRY_TELEPHONE
                )
            )
            ->add(
                'body',
                'textarea',
                array(
                    'label' => Label::ENQUIRY_BODY
                )
            )
            ->add(
                'submit',
                'submit'
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JamesMannion\ForumBundle\Entity\Enquiry'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jamesmannion_forumbundle_enquiry';
    }
}
