<?php

namespace Teclliure\QuestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Teclliure\QuestionBundle\Entity\Question;
use Teclliure\QuestionBundle\Entity\Answer;
use Teclliure\QuestionBundle\Form\QuestionType;
use Teclliure\QuestionBundle\Form\AnswerType;
/**
 * Questionary controller.
 *
 */
class QuestionController extends Controller
{
    /**
     * Saves a question
     *
     */
    public function saveQuestionAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $questionary = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $entity = $questionary->find($id);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        if ($request->isXmlHttpRequest()) {
            if ($request->get('questionId')) {
                $question = $em->getRepository('TeclliureQuestionBundle:Question')->find($request->get('questionId'));
            }
            else {
                $question = new Question();
            }

            $question->setQuestionary($entity);
            $questionForm = $this->createForm(new QuestionType(), $question);
            $questionForm->bind($request);

            if ($questionForm->isValid()) {
                $em->persist($question);
                $em->flush();

                $questionForm = $this->createForm(new QuestionType(), new Question());

                $this->get('session')->setFlash('info',
                    'Question saved correctly'
                );
                $questionFormError = false;
            }
            else {
                $this->get('session')->setFlash('error',
                    'Error on question save'
                );
                $questionFormError = true;
            }


            $questions = $questionary->findQuestions($entity);

            return $this->render(':ajax:base_ajax.html.twig', array(
                'template'          => 'TeclliureQuestionBundle:Question:question.html.twig',
                'entity'            => $entity,
                'questions'         => $questions,
                'questionForm'      => $questionForm->createView(),
                'questionFormError' => $questionFormError
            ));
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }

   /**
     * Sort question
     *
     */
    public function sortQuestionAction($questionId, $sortOrder)
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $questionId = trim(str_replace('questionId','',$questionId));

            $questionRepository = $em->getRepository('TeclliureQuestionBundle:Question');

            $entity = $questionRepository->find($questionId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Question entity.');
            }

            $entity->setPosition($sortOrder);
            $em->persist($entity);
            $em->flush();

            return new Response();
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }

    /**
     * Deletes a question
     *
     */
    public function deleteQuestionAction($questionId)
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $questionId = trim(str_replace('questionId','',$questionId));

            $questionRepository = $em->getRepository('TeclliureQuestionBundle:Question');
            $questionaryRepository = $em->getRepository('TeclliureQuestionBundle:Questionary');

            $entity = $questionRepository->find($questionId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Question entity.');
            }
            $questionary = $entity->getQuestionary();

            $em->remove($entity);

            try {
                $em->flush();

                $this->get('session')->setFlash('info',
                    'Question deleted correctly'
                );
            }
            catch (\Exception $e) {
                $this->get('session')->setFlash('error',
                    'Question could not be deleted. You should delete related content (answers, ...) before.'
                );
            }

            $questions = $questionaryRepository->findQuestions($questionary);



            return $this->render(':ajax:base_ajax.html.twig', array(
                'template'          => 'TeclliureQuestionBundle:Question:questionList.html.twig',
                'questions'   => $questions,
            ));
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }

    /**
     * Show answer form
     *
     */
    public function formAnswerAction($questionId)
    {
        $em = $this->getDoctrine()->getManager();

        $questionRepository = $em->getRepository('TeclliureQuestionBundle:Question');

        $entity = $questionRepository->find($questionId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $answerForm = $this->createForm(new AnswerType(), new Answer());

        return $this->render('TeclliureQuestionBundle:Question:answerForm.html.twig', array(
            'question'      => $entity,
            'answerForm' => $answerForm->createView()
        ));
    }

    /**
     * Saves a question
     *
     */
    public function saveAnswerAction($questionId)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $questionRepository = $em->getRepository('TeclliureQuestionBundle:Question');

        $entity = $questionRepository->find($questionId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        if ($request->isXmlHttpRequest()) {
            if ($request->get('answerId')) {
                $answer = $em->getRepository('TeclliureQuestionBundle:Answer')->find($request->get('answerId'));
            }
            else {
                $answer = new Answer();
            }

            $answer->setQuestion($entity);
            $answerForm = $this->createForm(new AnswerType(), $answer);
            $answerForm->bind($request);

            if ($answerForm->isValid()) {
                $em->persist($answer);
                $em->flush();

                $this->get('session')->setFlash('info',
                    'Answer saved correctly'
                );


               $viewParams = array(
                   'question'      => $entity
               );
            }
            else {
                $viewParams = array(
                    'question'      => $entity,
                    'answerForm' => $answerForm->createView()
                );
            }

            return $this->render(':ajax:base_ajax.html.twig', array_merge(array(
                'template'          => 'TeclliureQuestionBundle:Question:answerElement.html.twig'),
                $viewParams)
            );
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'

            ));
        }
    }

    /**
     * Deletes a question
     *
     */
    public function deleteAnswerAction($answerId)
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $answerRepository = $em->getRepository('TeclliureQuestionBundle:Answer');

            $entity = $answerRepository->find($answerId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Answer entity.');
            }
            $question = $entity->getQuestion();

            $em->remove($entity);
            $em->flush();

            $this->get('session')->setFlash('error',
                'Question DELETED correctly'
            );

            return $this->render(':ajax:base_ajax.html.twig', array(
                'template'      => 'TeclliureQuestionBundle:Question:answerList.html.twig',
                'question'      => $question
            ));
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }

    /**
     * Sort answer
     */
    public function sortAnswerAction($answerId, $sortOrder)
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $answerId = trim(str_replace('answerId','',$answerId));

            $answerRepository = $em->getRepository('TeclliureQuestionBundle:Answer');

            $entity = $answerRepository->find($answerId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Answer entity.');
            }

            $entity->setPosition($sortOrder);
            $em->persist($entity);
            $em->flush();

            return new Response();
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }
}