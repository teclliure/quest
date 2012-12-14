<?php

namespace Teclliure\QuestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ValidationRuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('rangeMin','integer')
            ->add('rangeMax','integer')
            ->add('help')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teclliure\QuestionBundle\Entity\ValidationRule'
        ));
    }

    public function getName()
    {
        return 'teclliure_questionbundle_validationruletype';
    }
}
