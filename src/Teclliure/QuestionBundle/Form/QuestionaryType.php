<?php

namespace Teclliure\QuestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Teclliure\CategoryBundle\Form\EventListener\CategoryFieldsSubscriber;

class QuestionaryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('active')
        ;

        $subscriber = new CategoryFieldsSubscriber($builder->getFormFactory());
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teclliure\QuestionBundle\Entity\Questionary'
        ));
    }

    public function getName()
    {
        return 'teclliure_questionbundle_questionarytype';
    }
}
