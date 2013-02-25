<?php

namespace Teclliure\PatientBundle\Controller;

use Teclliure\DashboardBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Teclliure\PatientBundle\Entity\Patient;
use Teclliure\QuestionBundle\Entity\PatientQuestionary;
use Teclliure\PatientBundle\Form\PatientType;
use Teclliure\QuestionBundle\Form\PatientQuestionaryType;
use Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation;
use Teclliure\QuestionBundle\Form\PatientQuestionaryValidationType;
use Symfony\Component\HttpFoundation\Response;

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

        if (!$this->getUser()->getIsAdmin()) {
            $userId = $this->getUser()->getId();
        }
        else {
            $userId = null;
        }

        $entities = $pager->paginate(
            $em->getRepository('TeclliurePatientBundle:Patient')->queryAllFromUser($userId, $searchString)
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
            throw $this->createNotFoundException('Unable to find Person entity.');
        }

        $this->checkPerms($entity);

        $questionaries = $questionaryRepository->findPatientQuestionaries($entity);
        $reports = $patientRepository->findPatientReports($entity);

        $patientForm = $this->createForm(new PatientType(), $entity);

        $this->buildBreadcrumbs('show', array('id'=>$entity->getId()));

        return $this->render('TeclliurePatientBundle:Patient:show.html.twig', array(
            'entity'            => $entity,
            'editForm'          => $patientForm->createView(),
            'reports'           => $reports,
            'questionaries'     => $questionaries
        ));
    }

    /**
     * Select questionary
     */
    public function selectQuestionaryAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $patientRepository = $em->getRepository('TeclliurePatientBundle:Patient');
        $questionaryRepository = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $entity = $patientRepository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Person entity.');
        }

        $this->checkPerms($entity);

        $searchForm = $form = $this->createFormBuilder(array())
            ->add('name', 'search', array('required' => false))
            ->getForm();

        $searchArray = null;
        if ($request->isMethod('POST')) {
            $searchForm->bind($request);

            if ($form->isValid()) {
                $searchArray = $searchForm->getData();
            }
        }
        $catQuestionaries = $questionaryRepository->getQuestionariesByCategory(true, $searchArray);

        $this->buildBreadcrumbs('select', array('id'=>$entity->getId()));

        if ($request->isXmlHttpRequest()) {
            return $this->render('TeclliurePatientBundle:Patient:questionariesList.html.twig', array(
                'entity'            => $entity,
                'catQuestionaries'  => $catQuestionaries
            ));
        }
        else {
            return $this->render('TeclliurePatientBundle:Patient:selectQuestionary.html.twig', array(
                'entity'            => $entity,
                'catQuestionaries'  => $catQuestionaries,
                'searchForm'        => $searchForm->createView()
            ));
        }
    }

    /**
     * Create questionary
     */
    public function createQuestionaryAction(Request $request, $id, $questionaryId, $patientQuestionaryId)
    {
        $em = $this->getDoctrine()->getManager();

        $patientRepository = $em->getRepository('TeclliurePatientBundle:Patient');
        $questionaryRepository = $em->getRepository('TeclliureQuestionBundle:Questionary');
        $patientQuestionaryRepository = $em->getRepository('TeclliureQuestionBundle:PatientQuestionary');

        $patient = $patientRepository->find($id);
        $questionary = $questionaryRepository->find($questionaryId);

        if (!$patient) {
            throw $this->createNotFoundException('Unable to find Person entity.');
        }

        if (!$questionary) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        $this->checkPerms($patient);
        $docs = $questionaryRepository->getDocs($questionary);

        if ($patientQuestionaryId) {
            $patientQuestionary = $patientQuestionaryRepository->find($patientQuestionaryId);
            if (!$patientQuestionary) {
                throw $this->createNotFoundException('Unable to find PersonQuestionary entity.');
            }
        }
        else {
            $patientQuestionary = new PatientQuestionary();
            $patientQuestionary->setPatient($patient);
            $patientQuestionary->setQuestionary($questionary);
        }
        $patientQuestionaryForm = $this->createForm('teclliure_questionbundle_patientquestionarytype', $patientQuestionary);

        if ($request->isMethod('POST')) {
            $patientQuestionaryForm->bind($request);
            if ($patientQuestionaryForm->isValid()) {
                $em->getConnection()->beginTransaction(); // suspend auto-commit
                try
                {
                    $patientQuestionary->setUpdated(new \DateTime());
                    $em->persist($patientQuestionary);
                    $em->flush();

                    $patientQuestionary->doSaveAnswers($em);
                    $em->flush();
                    $em->getConnection()->commit();

                    $this->get('session')->setFlash('notice',
                        'Person questionary saved correctly'
                    );

                    return $this->redirect($this->generateUrl('questionary_patient_validation', array('id' => $patientQuestionary->getId())));
                }
                catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    throw $e;
                }

            }
            else
            {
                $this->get('session')->setFlash('error',
                    'Error saving Person questionary'
                );
            }
        }

        if ($patientQuestionary->getId())
        {
            $this->buildBreadcrumbs('questionaryEdit', array('id'=>$patient->getId(),'questionaryId'=>$questionary->getId(), 'patientQuestionaryId'=>$patientQuestionaryId));
        }
        else
        {
            $this->buildBreadcrumbs('create', array('id'=>$patient->getId(),'questionaryId'=>$questionary->getId()));
        }


        return $this->render('TeclliurePatientBundle:Patient:createQuestionary.html.twig', array(
            'patient'                   => $patient,
            'questionary'               => $questionary,
            'patientQuestionary'        => $patientQuestionary,
            'patientQuestionaryForm'    => $patientQuestionaryForm->createView(),
            'docs'                      => $docs
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
                throw $this->createNotFoundException('Unable to find Person entity.');
            }

            $this->checkPerms($entity);
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
                if (!$entity->getUser()) {
                    $entity->setUser($this->getUser());
                }
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
                'Person saved correctly'
            );
            $correctlySaved = true;
        }
        else {
            $this->get('session')->setFlash('error',
                'Error saving Person'
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
            throw $this->createNotFoundException('Unable to find Person entity.');
        }
        $this->checkPerms($entity);

        try {
            $em->remove($entity);
            $em->flush();

            $this->get('session')->setFlash('notice',
                'Person DELETED correctly'
            );

            return $this->redirect($this->generateUrl('home'));
        }
        catch (\Exception $e) {
            $this->get('session')->setFlash('error',
                'Person could NOT BE DELETED because it contains associated data. Delete the associated data before you can delete it.'
            );
            return $this->redirect($this->generateUrl('patient_show', array('id' => $entity->getId())));
        }
    }

    public function deletePatientQuestionaryAction($id) {
        $em = $this->getDoctrine()->getManager();
        $patientQuestionaryRepository = $em->getRepository('TeclliureQuestionBundle:PatientQuestionary');

        $entity = $patientQuestionaryRepository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonQuestionary entity.');
        }

        $this->checkPerms($entity);

        $patientId = $entity->getPatient()->getId();
        $patientQuestionaryRepository->deleteAnswers($entity);
        $em->remove($entity);
        $em->flush();

        $this->get('session')->setFlash('notice',
            'PersonQuestionary DELETED correctly'
        );

        return $this->redirect($this->generateUrl('patient_show', array('id' => $patientId)));
    }

    public function reloadPatientContentAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TeclliurePatientBundle:Patient')->find($id);

        $this->checkPerms($entity);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Person entity.');
        }

        return $this->render('TeclliurePatientBundle:Patient:showContent.html.twig', array(
            'entity'          => $entity,
        ));
    }

    public function validationPatientQuestionaryAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TeclliureQuestionBundle:PatientQuestionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonQuestionary entity.');
        }
        $this->checkPerms($entity);
        $patientQuestionaryForm = $this->createForm(new PatientQuestionaryValidationType(), $entity);

        $this->buildBreadcrumbs('validations', array(
            array('id'=>$entity->getPatient()->getId(),'questionaryId'=>$entity->getQuestionary()->getId(), 'patientQuestionaryId'=>$entity->getId()),
            array('id'=>$entity->getId())
        ));

        if ($this->getRequest()->isMethod('POST')) {
            $patientQuestionaryForm->bind($this->getRequest());
            if ($patientQuestionaryForm->isValid()) {
                $em->persist($entity);

                /*foreach($entity->getValidations() as $validation) {
                    $em->persist($validation);
                }*/
                $em->flush();

                $this->get('session')->setFlash('notice',
                    'Person questionary validations saved correctly'
                );

                return $this->redirect($this->generateUrl('questionary_patient_results', array('id' => $entity->getId())));
            }
        }
        return $this->render('TeclliurePatientBundle:Validation:selectValidations.html.twig', array(
            'entity'          => $entity,
            'patientQuestionaryForm' => $patientQuestionaryForm->createView()
        ));
    }

    public function resultsPatientQuestionaryAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TeclliureQuestionBundle:PatientQuestionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonQuestionary entity.');
        }
        $this->checkPerms($entity);
        $results = $em->getRepository('TeclliureQuestionBundle:PatientQuestionary')->calculateResults($entity);

        $this->buildBreadcrumbs('results', array(
            array('id'=>$entity->getPatient()->getId(),'questionaryId'=>$entity->getQuestionary()->getId(), 'patientQuestionaryId'=>$entity->getId()),
            array('id'=>$entity->getId()),
            array('id'=>$entity->getId())
        ));

        return $this->render('TeclliurePatientBundle:Validation:validationsResults.html.twig', array(
            'entity'          => $entity,
            'results' => $results
        ));
    }

    public function disableQuestionsAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TeclliureQuestionBundle:Questionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }
        $questions = $entity->getQuestions();
        $response = new Response();
        $response->headers->set('Content-Type', 'text/javascript');
        $response->setContent($this->renderView('TeclliurePatientBundle:Patient:disableQuestions.js.twig', array(
            'questions'          => $questions
        )));

        return $response;
    }

    public function getBreadcrumbsRoutes() {
        return array(
            'list' => array('route'=>'home', 'label'=>'List persons'),
            'show' => array('route'=>'patient_show', 'label'=>'Person Show'),
            'new' => array('route'=>'patient_new', 'label'=>'Person Create'),
            'edit' => array('route'=>'patient_edit', 'label'=>'Person Edit'),
            'select' => array('route'=>'questionary_patient_new', 'label'=>'Select Questionary'),
            'create' => array('route'=>'questionary_patient_create', 'label'=>'Create Questionary'),
            'questionaryEdit' => array('route'=>'questionary_patient_create', 'label'=>'Edit questionary'),
            'validations' => array(
                array('route'=>'questionary_patient_create', 'label'=>'Edit questionary'),
                array('route'=>'questionary_patient_validation', 'label'=>'Validations')
                ),
            'results' => array(
                array('route'=>'questionary_patient_create', 'label'=>'Edit questionary'),
                array('route'=>'questionary_patient_validation', 'label'=>'Validations'),
                array('route'=>'questionary_patient_results', 'label'=>'Results')
            )
        );
    }
}