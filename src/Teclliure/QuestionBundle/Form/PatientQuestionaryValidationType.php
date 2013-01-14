<?php

namespace Teclliure\QuestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

class PatientQuestionaryValidationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();

        $questionary = $data->getQuestionary();

        /*$transformer = new PatientQuestionaryValidationToNumberTransformer($this->em);

        $builder->add(
            $builder->create('validations', 'entity', array(
                      'class'    => 'TeclliureQuestionBundle:Validation' ,
                      'property' => 'name',
                      'expanded' => true ,
                      'multiple' => true,
                      'query_builder' => function(EntityRepository $er) use ($questionary) {
                            return $er->createQueryBuilder('q')
                               ->where('q.questionary = :questionary')
                               ->setParameter('questionary',$questionary->getId())
                               ->orderBy('q.name', 'ASC');
                      },
            ))->addModelTransformer($transformer)
        );*/

        $builder->add('validations', 'entity', array(
                'class'    => 'TeclliureQuestionBundle:Validation' ,
                'property' => 'name',
                'expanded' => true ,
                'multiple' => true,
                'query_builder' => function(EntityRepository $er) use ($questionary) {
                    return $er->createQueryBuilder('q')
                        ->where('q.questionary = :questionary')
                        ->setParameter('questionary',$questionary->getId())
                        ->orderBy('q.name', 'ASC');
                },
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teclliure\QuestionBundle\Entity\PatientQuestionary'
        ));
    }

    public function getName()
    {
        return 'teclliure_questionbundle_patientquestionary_validationtype';
    }
}
