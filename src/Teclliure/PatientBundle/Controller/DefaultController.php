<?php

namespace Teclliure\PatientBundle\Controller;

use Teclliure\DashboardBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Teclliure\PatientBundle\Entity\Patient;
use Teclliure\PatientBundle\Form\PatientType;

class DefaultController extends Controller
{
    /**
     * Finds and displays current user Patients.
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pager = $this->get('ideup.simple_paginator');
        $pager->setItemsPerPage('20');

        $searchString = $request->get('searchString');

        $entities = $pager->paginate(
            $em->getRepository('TeclliurePatientBundle:Patient')->queryAllFromUser($this->getUser()->getId(), $searchString)
        )->getResult();

        $this->buildBreadcrumbs('list');

        return $this->render('TeclliurePatientBundle:Patient:index.html.twig', array(
            'entities' => $entities,
            'searchString' => $searchString
        ));
    }

    /**
     * Finds and displays a Patient entity.
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $patientRepository = $em->getRepository('TeclliurePatientBundle:Patient');
        $questionaryRepository = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $entity = $patientRepository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Patient entity.');
        }

        $questionaries = $questionaryRepository->findPatientQuestionaries($entity);

        $patientForm = $this->createForm(new PatientType(), $entity);

        $this->buildBreadcrumbs('show');

        return $this->render('TeclliurePatientBundle:Patient:show.html.twig', array(
            'entity'            => $entity,
            'editForm'          => $patientForm->createView(),
            'questionaries'     => $questionaries
        ));
    }

    /**
     * Select questionary
     */
    public function selectQuestionaryAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $patientRepository = $em->getRepository('TeclliurePatientBundle:Patient');
        $questionaryRepository = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $entity = $patientRepository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Patient entity.');
        }

        $catQuestionaries = $questionaryRepository->getQuestionariesByCategory();

        $this->buildBreadcrumbs('select');

        return $this->render('TeclliurePatientBundle:Patient:selectQuestionary.html.twig', array(
            'entity'            => $entity,
            'catQuestionaries'  => $catQuestionaries
        ));
    }

    /**
     * Create questionary
     */
    public function selectQuestionaryAction($id, $questionaryId)
    {
        $em = $this->getDoctrine()->getManager();

        $patientRepository = $em->getRepository('TeclliurePatientBundle:Patient');
        $questionaryRepository = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $patient = $patientRepository->find($id);
        $questionary = $patientRepository->find($questionaryId);

        if (!$patient) {
            throw $this->createNotFoundException('Unable to find Patient entity.');
        }

        if (!$questionary) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        $this->buildBreadcrumbs('create');

        return $this->render('TeclliurePatientBundle:Patient:createQuestionary.html.twig', array(
            'patient'            => $patient,
            'questionary'       => $questionary
        ));
    }

    /**
     * Creates new patient
     */
    public function newAction()
    {
        $patientForm = $this->createForm(new PatientType(), new Patient());

        $this->buildBreadcrumbs('new');

        return $this->render('TeclliurePatientBundle:Patient:new.html.twig', array(
            'editForm'          => $patientForm->createView(),
        ));
    }

    /**
     * Edits an existing Patient entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if ($id) {
            $entity = $em->getRepository('TeclliurePatientBundle:Patient')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Patient entity.');
            }
        }
        else {
            $entity = new Patient();
        }

        $editForm = $this->createForm(new PatientType(), $entity);
        $editForm->bind($request);

        $correctlySaved = false;
        if ($editForm->isValid()) {
            $em->getConnection()->beginTransaction(); // suspend auto-commit
            try
            {
                $em->persist($entity);
                $em->flush();

                $em->getConnection()->commit();
            }
            catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                throw $e;
            }
            $this->get('session')->setFlash('notice',
                'Patient saved correctly'
            );
            $correctlySaved = true;
        }
        else {
            $this->get('session')->setFlash('error',
                'Error saving Patient'
            );
        }

        if ($id) {
            return $this->render(':ajax:base_ajax.html.twig', array(
                'template'          => 'TeclliurePatientBundle:Patient:editForm.html.twig',
                'entity'            => $entity,
                'editForm'          => $editForm->createView()
            ));
        }
        else {
            if ($correctlySaved) {
                return $this->redirect($this->generateUrl('patient_show', array('id' => $entity->getId())));
            }
            $this->buildBreadcrumbs('new');

            return $this->render('TeclliurePatientBundle:Patient:new.html.twig', array(
                'editForm'          => $editForm->createView(),
            ));
        }
    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TeclliurePatientBundle:Patient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Patient entity.');
        }

        $this->get('session')->setFlash('notice',
            'Patient DELETED correctly'
        );

        return $this->redirect($this->generateUrl('home'));
    }

    public function reloadPatientContentAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TeclliurePatientBundle:Patient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Patient entity.');
        }

        return $this->render('TeclliurePatientBundle:Patient:showContent.html.twig', array(
            'entity'          => $entity,
        ));
    }

    public function getBreadcrumbsRoutes() {
        return array(
            'list' => array('route'=>'home', 'label'=>'List patients'),
            'show' => array('route'=>'patient_show', 'label'=>'Patient Show'),
            'new' => array('route'=>'patient_new', 'label'=>'Patient Create'),
            'select' => array('route'=>'patient_new', 'label'=>'Select Questionary'),
            'create' => array('route'=>'patient_new', 'label'=>'Create Questionary'),
            'edit' => array('route'=>'patient_edit', 'label'=>'Patient Edit'),
        );
    }
}