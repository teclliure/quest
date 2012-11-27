<?php

namespace Teclliure\QuestionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Teclliure\QuestionBundle\Entity\Questionary;
use Teclliure\QuestionBundle\Entity\Question;
use Teclliure\QuestionBundle\Entity\Answer;
use Teclliure\QuestionBundle\Form\QuestionaryType;
use Teclliure\QuestionBundle\Form\QuestionType;
use Teclliure\QuestionBundle\Form\AnswerType;


/**
 * Questionary controller.
 *
 */
class QuestionaryController extends Controller
{
    /**
     * Lists all Questionary entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pager = $this->get('ideup.simple_paginator');

        $entities = $pager->paginate($em->getRepository('TeclliureQuestionBundle:Questionary')->queryAll())->getResult();

        return $this->render('TeclliureQuestionBundle:Questionary:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Questionary entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $questionRepository = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $entity = $questionRepository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }
        $questions = $questionRepository->findQuestions($entity);

        $questionForm = $this->createForm(new QuestionType(), new Question());

        return $this->render('TeclliureQuestionBundle:Questionary:show.html.twig', array(
            'entity'      => $entity,
            'questions'   => $questions,
            'questionForm' => $questionForm->createView()
        ));
    }

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
                $questionFormError = true;
            }


            $questions = $questionary->findQuestions($entity);

            return $this->render('TeclliureQuestionBundle:Questionary:question.html.twig', array(
                'entity'      => $entity,
                'questions'   => $questions,
                'questionForm' => $questionForm->createView(),
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
     * Displays a form to create a new Questionary entity.
     *
     */
    public function newAction()
    {
        $entity = new Questionary();
        $form   = $this->createForm(new QuestionaryType(), $entity);

        return $this->render('TeclliureQuestionBundle:Questionary:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Questionary entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Questionary();
        $form = $this->createForm(new QuestionaryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('questionary_show', array('id' => $entity->getId())));
        }

        return $this->render('TeclliureQuestionBundle:Questionary:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Questionary entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TeclliureQuestionBundle:Questionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        $editForm = $this->createForm(new QuestionaryType(), $entity);

        return $this->render('TeclliureQuestionBundle:Questionary:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
     * Edits an existing Questionary entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TeclliureQuestionBundle:Questionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        $editForm = $this->createForm(new QuestionaryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('questionary_edit', array('id' => $id)));
        }

        return $this->render('TeclliureQuestionBundle:Questionary:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
     * Deletes a Questionary entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TeclliureQuestionBundle:Questionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('questionary'));
    }

    /**
     * Saves a question
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
     * Saves a question
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
            $em->flush();

            $questions = $questionaryRepository->findQuestions($questionary);

            return $this->render('TeclliureQuestionBundle:Questionary:questionList.html.twig', array(
                'questions'   => $questions,
            ));
            return new Response();
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

        return $this->render('TeclliureQuestionBundle:Questionary:answerForm.html.twig', array(
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

            return $this->render('TeclliureQuestionBundle:Questionary:answerElement.html.twig', $viewParams);


        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'

            ));
        }
    }

}
