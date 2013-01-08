<?php

namespace Teclliure\QuestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Teclliure\CategoryBundle\Form\EventListener\CategoryFieldsSubscriber;

class QuestionaryType extends AbstractType
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $modelRepository = $this->em->getRepository('TeclliureQuestionBundle:Questionary');
        $subscriber = new CategoryFieldsSubscriber($builder->getFormFactory(), $this->em, $modelRepository);

        $builder
            ->add('name')
            ->add('description')
            ->add('active','checkbox', array('required' => false))
        ;

        $builder->addEventSubscriber($subscriber);
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
