<?php

namespace Teclliure\PatientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('identification')
            ->add('email')
            ->add('phone')
            ->add('address')
            ->add('birthDate','date')
            ->add('notes')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teclliure\PatientBundle\Entity\Patient'
        ));
    }

    public function getName()
    {
        return 'teclliure_patientbundle_patienttype';
    }
}
