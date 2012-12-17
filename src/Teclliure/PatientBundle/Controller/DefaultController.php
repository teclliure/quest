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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pager = $this->get('ideup.simple_paginator');
        $pager->setItemsPerPage('20');

        $entities = $pager->paginate($em->getRepository('TeclliurePatientBundle:Patient')->queryAllFromUser($this->getUser()->getId()))->getResult();

        $this->buildBreadcrumbs('list');

        return $this->render('TeclliureDashboardBundle:Patient:index.html.twig', array(
            'entities' => $entities,
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
     * Edits an existing Questionary entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if ($id) {
            $entity = $em->getRepository('TeclliurePatientBundle:Patient')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Questionary entity.');
            }
        }
        else {
            $entity = new Patient();
        }

        $editForm = $this->createForm(new PatientType(), $entity);
        $editForm->bind($request);

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
            return $this->redirect($this->generateUrl('patient_show', array('id' => $entity->getId())));
        }

        $this->buildBreadcrumbs('edit');

        $this->get('session')->setFlash('error',
            'Error saving Patient'
        );

        if ($id) {
            return $this->render('TeclliureQuestionBundle:Questionary:editForm.html.twig', array(
                'entity'      => $entity,
                'editForm'   => $editForm->createView()
            ));
        }
        else {
            return $this->render('TeclliurePatientBundle:Patient:new.html.twig', array(
                'editForm'          => $patientForm->createView(),
            ));
        }
    }

    public function getBreadcrumbsRoutes() {
        return array(
            'list' => array('route'=>'home', 'label'=>'List patients'),
            'show' => array('route'=>'patient_show', 'label'=>'Patient Show'),
            'new' => array('route'=>'patient_new', 'label'=>'Patient Create'),
            'edit' => array('route'=>'patient_edit', 'label'=>'Patient Edit'),
        );
    }
}