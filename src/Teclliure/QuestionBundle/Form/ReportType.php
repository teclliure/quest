<?php

namespace Teclliure\QuestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();

        $patient = $data->getPatient();

        $builder
            ->add('name')
            ->add('description')
            ->add('patientQuestionaries', 'entity', array(
                'class'    => 'TeclliureQuestionBundle:PatientQuestionary' ,
                'property' => 'questionary',
                'expanded' => true ,
                'multiple' => true,
                'label'    => 'Questionaries',
                'query_builder' => function(EntityRepository $er) use ($patient) {
                    return $er->createQueryBuilder('q')
                        ->where('q.patient = :patient')
                        ->setParameter('patient', $patient->getId())
                        ->orderBy('q.created','DESC')
                        ->addOrderBy('q.updated', 'ASC');
                },
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teclliure\QuestionBundle\Entity\Report'
        ));
    }

    public function getName()
    {
        return 'teclliure_questionbundle_report_type';
    }
}