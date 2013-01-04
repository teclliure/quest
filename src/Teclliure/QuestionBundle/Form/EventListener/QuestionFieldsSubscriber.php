<?php

namespace Teclliure\QuestionBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Teclliure\QuestionBundle\Form\QuestionWithAnswersType;

class QuestionFieldsSubscriber implements EventSubscriberInterface
{
    private $factory;

    private $em;

    public function __construct(FormFactoryInterface $factory, EntityManager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(
            FormEvents::POST_SET_DATA => 'preSetData',
             FormEvents::POST_BIND => 'postBind',
        );
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. You're only concerned with when
        // setData is called with an actual Entity object in it (whether new
        // or fetched with Doctrine). This if statement lets you skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

        $currentVal = null;
        $questionaryRepository = $this->em->getRepository('TeclliureQuestionBundle:Questionary');
        $questions = $questionaryRepository->findQuestions($data->getQuestionary());

        foreach ($questions as $key=>$question) {
            // $builder->add('patientQuestionaryAnswers', new QuestionWithAnswersType($question));
            $questionType = new QuestionWithAnswersType();
            $questionType->setQuestion($question);
            $form->add($this->factory->createNamed(
                'patientQuestionaryAnswers'.$question->getId(),
                $questionType,
                $currentVal,
                array(
                    'mapped'    => false,
                    'attr'      => array('class' => 'patientQuestionContent'),
                    'label_attr' => array('class' => 'patientQuestionLabel'),
                    'label'     => '<span class="tooltiplink" title="'.$question->getQuestion().'" data-content="'.$question->getHelp().'">'.($key+1).'.- '.$question->getQuestion().'</span>',
                    'required'  => true,
                )
            ));
        }
    }

    public function postBind(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // $form->add($this->factory->createNamed('catW', 'text'));

        // Add category selectors
        $questionaryRepository = $this->em->getRepository('TeclliureQuestionBundle:Questionary');
        $questions = $questionaryRepository->findQuestions($data->getQuestionary());

        $data->answersTmp = array();
        foreach ($questions as $question) {
            $data->answersTmp[] = $form['patientQuestionaryAnswers'.$question->getId()]->getViewData();
        }
    }
}

