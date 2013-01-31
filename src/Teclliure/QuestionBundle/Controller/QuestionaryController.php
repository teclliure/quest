<?php

namespace Teclliure\QuestionBundle\Controller;

use Teclliure\DashboardBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Teclliure\QuestionBundle\Entity\Questionary;
use Teclliure\QuestionBundle\Entity\Question;
use Teclliure\QuestionBundle\Entity\Validation;
use Teclliure\QuestionBundle\Form\QuestionaryType;
use Teclliure\QuestionBundle\Form\ValidationType;
use Teclliure\QuestionBundle\Form\QuestionType;


use Knp\Menu\FactoryInterface as MenuFactoryInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Knp\Menu\MenuItem;

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
        $pager->setItemsPerPage('10');

        $entities = $pager->paginate($em->getRepository('TeclliureQuestionBundle:Questionary')->queryAll())->getResult();

        $this->buildBreadcrumbs('list');

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

        $questionaryRepository = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $entity = $questionaryRepository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        $questions = $questionaryRepository->findQuestions($entity);
        $validations = $questionaryRepository->findValidations($entity);

        $questionForm = $this->createForm(new QuestionType(), new Question());
        $validationForm = $this->createForm(new ValidationType(), new Validation());

        $this->buildBreadcrumbs('show', array('id'=>$entity->getId()));

        return $this->render('TeclliureQuestionBundle:Questionary:show.html.twig', array(
            'entity'      => $entity,
            'questions'   => $questions,
            'questionForm' => $questionForm->createView(),
            'validations'   => $validations,
            'validationForm' => $validationForm->createView()

        ));
    }

    /**
     * Displays a form to create a new Questionary entity.
     *
     */
    public function newAction()
    {
        $entity = new Questionary();
        $form   = $this->createForm('teclliure_questionbundle_questionarytype', $entity);

        $this->buildBreadcrumbs('new');

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
        $form = $this->createForm('teclliure_questionbundle_questionarytype', $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->getConnection()->beginTransaction(); // suspend auto-commit
            try
            {
                $em->persist($entity);
                $em->flush();

                $entity->doSaveSubcategories($em);
                $em->flush();
                $em->getConnection()->commit();

                $this->get('session')->setFlash('info',
                    'Questionary saved correctly'
                );
            }
            catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('questionary_show', array('id' => $entity->getId())));
        }

        $this->buildBreadcrumbs('new');

        $this->get('session')->setFlash('error',
            'Error saving Questionary'
        );

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

        $editForm = $this->createForm('teclliure_questionbundle_questionarytype', $entity);

        $this->buildBreadcrumbs('edit', array('id'=>$entity->getId()));

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

        $editForm = $this->createForm('teclliure_questionbundle_questionarytype', $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->getConnection()->beginTransaction(); // suspend auto-commit
            try
            {
                $em->persist($entity);
                $em->flush();

                $entity->doSaveSubcategories($em);
                $em->flush();
                $em->getConnection()->commit();

                $this->get('session')->setFlash('notice',
                    'Questionary saved'
                );
            }
            catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                throw $e;
            }
            return $this->redirect($this->generateUrl('questionary_edit', array('id' => $id)));
        }

        $this->buildBreadcrumbs('edit', array('id'=>$entity->getId()));

        $this->get('session')->setFlash('error',
            'Error saving Questionary'
        );

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
        $entity->doDeleteSubcategories($em);
        $em->flush();

        $this->get('session')->setFlash('info',
            'Questionary correctly deleted'
        );

        return $this->redirect($this->generateUrl('questionary'));
    }


    public function getBreadcrumbsRoutes() {
        return array(
            'list' => array('route'=>'questionary', 'label'=>'List questionaries'),
            'show' => array('route'=>'questionary_show', 'label'=>'Questionary Show'),
            'new' => array('route'=>'questionary_new', 'label'=>'Questionary Create'),
            'edit' => array('route'=>'questionary_edit', 'label'=>'Questionary Edit'),
        );
    }


}
