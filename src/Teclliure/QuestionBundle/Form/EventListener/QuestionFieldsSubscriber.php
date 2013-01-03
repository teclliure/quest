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

        $questionaryRepository = $this->em->getRepository('TeclliureQuestionBundle:Questionary');
        $questions = $questionaryRepository->findQuestions($data->getQuestionary());

        $transformer = new PatientQuestionaryAnswerToNumberTransformer($this->em);

        foreach ($questions as $question) {
            // $builder->add('patientQuestionaryAnswers', new QuestionWithAnswersType($question));
            $questionType = new QuestionWithAnswersType();
            $questionType->setQuestion($question);
            $form->add($this->factory->createNamed('patientQuestionaryAnswers', $questionType)->addModelTransformer($transformer));
        }
    }
}

