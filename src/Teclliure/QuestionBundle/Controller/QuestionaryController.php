<?php

namespace Teclliure\QuestionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Teclliure\QuestionBundle\Entity\Questionary;
use Teclliure\QuestionBundle\Entity\Question;
use Teclliure\QuestionBundle\Form\QuestionaryType;
use Teclliure\QuestionBundle\Form\QuestionType;

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
        $question = new Question();
        // $question->se
        $questionForm = $this->createForm(new QuestionType(), $question);


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
    public function saveQuestionAction($id, $questionId = null)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $questionRepository = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $entity = $questionRepository->find($id);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        if ($request->isXmlHttpRequest()) {
            if ($questionId) {
                $question = $em->getRepository('TeclliureQuestionBundle:Questionary')->find($questionId);
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
            }

            $questions = $questionRepository->findQuestions($entity);

            return $this->render('TeclliureQuestionBundle:Questionary:question.html.twig', array(
                'entity'      => $entity,
                'questions'   => $questions,
                'questionForm' => $questionForm->createView()
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
}
