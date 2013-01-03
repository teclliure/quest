<?php

namespace Teclliure\QuestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Teclliure\QuestionBundle\Form\EventListener\QuestionFieldsSubscriber;
use Symfony\Component\Form\FormEvents;

class PatientQuestionaryType extends AbstractType
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('notes');

        $subscriber = new QuestionFieldsSubscriber($builder->getFormFactory(), $this->em);
        $builder->addEventSubscriber($subscriber);

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teclliure\QuestionBundle\Entity\PatientQuestionary',
            'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'teclliure_questionbundle_patientquestionarytype';
    }
}
