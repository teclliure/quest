<?php

namespace Teclliure\QuestionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Teclliure\QuestionBundle\Entity\Questionary;
use Teclliure\QuestionBundle\Form\QuestionaryType;

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

        $entity = $em->getRepository('TeclliureQuestionBundle:Questionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TeclliureQuestionBundle:Questionary:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
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
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TeclliureQuestionBundle:Questionary:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new QuestionaryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('questionary_edit', array('id' => $id)));
        }

        return $this->render('TeclliureQuestionBundle:Questionary:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Questionary entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TeclliureQuestionBundle:Questionary')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Questionary entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('questionary'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
