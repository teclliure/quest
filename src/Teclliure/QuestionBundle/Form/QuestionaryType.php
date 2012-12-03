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
        $builder
            ->add('name')
            ->add('description')
            ->add('active')
        ;

        $subscriber = new CategoryFieldsSubscriber($builder->getFormFactory(), $this->em);
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
