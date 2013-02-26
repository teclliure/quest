<?php

namespace Teclliure\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('email','email')
             ->add('plainPassword', 'repeated', array(
                 'type' => 'password',
                 'required' => false,
                 'invalid_message' => 'Two passwords must be the same',
                 'first_options' => array('label' => 'Password'),
                 'second_options' => array('label' => 'Repeat password')
             ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teclliure\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'teclliure_userbundle_userregistertype';
    }
}