<?php

namespace Teclliure\QuestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

class AnswerQuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $questionary = $data->getQuestion()->getQuestionary();

        $builder->add('disabledQuestions', 'entity', array(
                'class'    => 'TeclliureQuestionBundle:Question',
                'property' => 'questionHelp',
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function(EntityRepository $er) use ($questionary) {
                    return $er->createQueryBuilder('q')
                        ->where('q.questionary = :questionary')
                        ->setParameter('questionary', $questionary->getId())
                        ->orderBy('q.category','DESC')
                        ->addOrderBy('q.position', 'ASC');
                },
            )
        );

        // $builder->add('disabledQuestions');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teclliure\QuestionBundle\Entity\Answer'
        ));
    }

    public function getName()
    {
        return 'teclliure_questionbundle_answerquestions_type';
    }
}