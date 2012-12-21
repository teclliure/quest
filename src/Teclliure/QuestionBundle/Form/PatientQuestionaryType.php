<?php

namespace Teclliure\QuestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Teclliure\QuestionBundle\Form\EventListener\QuestionsFieldsSubscriber;

class PatientQuestionaryType extends AbstractType
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('notes')
        ;

        $questionaryRepository = $this->em->getRepository('TeclliureQuestionBundle:Questionary');
        $questions = $questionaryRepository->findQuestions($entity);

        foreach ($questions as $question) {
            $builder->add('patientQuestionaryAnswers', new QuestionWithAnswersType($question));
        }
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
