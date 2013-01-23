<?php

namespace Teclliure\QuestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Teclliure\QuestionBundle\Entity\Validation;
use Teclliure\QuestionBundle\Entity\ValidationRule;
use Teclliure\QuestionBundle\Form\ValidationType;
use Teclliure\QuestionBundle\Form\ValidationRuleType;
use Teclliure\QuestionBundle\Form\ValidationQuestionsType;

use Knp\Menu\FactoryInterface as MenuFactoryInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Knp\Menu\MenuItem;

/**
 * Validation controller.
 *
 */
class ValidationController extends Controller
{
    /**
     * Loads question edit validation
     */
    public function editValidationAction($validationId)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $validationRepository = $em->getRepository('TeclliureQuestionBundle:Validation');

        $entity = $validationRepository->find($validationId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Validation entity.');
        }

        if ($request->isXmlHttpRequest()) {
            $validationForm = $this->createForm(new ValidationType(), $entity);

            return $this->render(':ajax:base_ajax.html.twig', array(
                'template'          => 'TeclliureQuestionBundle:Validation:validationForm.html.twig',
                'entity'            => $entity->getQuestionary(),
                'validationForm'      => $validationForm->createView(),
                'validationFormError' => true
            ));
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }

    /**
     * Saves a validation
     *
     */
    public function saveValidationAction($id, $validationId)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $questionary = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $entity = $questionary->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        if ($request->isXmlHttpRequest()) {
            if ($validationId) {
                $validation = $em->getRepository('TeclliureQuestionBundle:Validation')->find($validationId);
            }
            else {
                $validation = new Validation();
            }

            $validation->setQuestionary($entity);
            $validationForm = $this->createForm(new ValidationType(), $validation);
            $validationForm->bind($request);

            if ($validationForm->isValid()) {
                $em->persist($validation);
                $em->flush();

                $validationForm = $this->createForm(new ValidationType(), new Validation());

                $this->get('session')->setFlash('info',
                    'Validation saved correctly'
                );
                $validationFormError = false;
            }
            else {
                $this->get('session')->setFlash('error',
                    'Error on validation save'
                );
                $validationFormError = true;
            }


            $validations = $questionary->findValidations($entity);

            return $this->render(':ajax:base_ajax.html.twig', array(
                'template'             => 'TeclliureQuestionBundle:Validation:validation.html.twig',
                'entity'               => $entity,
                'validations'          => $validations,
                'validationForm'       => $validationForm->createView(),
                'validationFormError'  => $validationFormError
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
    public function sortValidationAction($validationId, $sortOrder)
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $validationId = trim(str_replace('validationId','',$validationId));

            $validationRepository = $em->getRepository('TeclliureQuestionBundle:Validation');

            $entity = $validationRepository->find($validationId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Validation entity.');
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
     * Deletes a validation
     *
     */
    public function deleteValidationAction($validationId)
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $validationId = trim(str_replace('validationId','',$validationId));

            $validationRepository = $em->getRepository('TeclliureQuestionBundle:Validation');
            $questionaryRepository = $em->getRepository('TeclliureQuestionBundle:Questionary');

            $entity = $validationRepository->find($validationId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Validation entity.');
            }
            $questionary = $entity->getQuestionary();

            $em->remove($entity);

            try {
                $em->flush();

                $this->get('session')->setFlash('info',
                    'Validation deleted correctly'
                );
            }
            catch (\Exception $e) {
                $this->get('session')->setFlash('error',
                    'Validation could not be deleted. You should delete related content (rules, user contents,...) before.'
                );
            }

            $validations = $questionaryRepository->findValidations($questionary);

            return $this->render(':ajax:base_ajax.html.twig', array(
                'template'          => 'TeclliureQuestionBundle:Validation:validationList.html.twig',
                'validations'   => $validations,
            ));
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }

    /**
     * Show rule form
     *
     */
    public function formRuleAction($validationId, $ruleId)
    {
        $em = $this->getDoctrine()->getManager();

        $validationRepository = $em->getRepository('TeclliureQuestionBundle:Validation');
        $ruleRepository = $em->getRepository('TeclliureQuestionBundle:ValidationRule');

        $entity = $validationRepository->find($validationId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Validation entity.');
        }

        if ($ruleId) {
            $rule = $ruleRepository->find($ruleId);

            if (!$rule) {
                throw $this->createNotFoundException('Unable to find Rule entity.');
            }
        }
        else {
            $rule = new ValidationRule();
        }

        $ruleForm = $this->createForm(new ValidationRuleType(), $rule);

        return $this->render('TeclliureQuestionBundle:Validation:ruleForm.html.twig', array(
            'validation'      => $entity,
            'ruleForm' => $ruleForm->createView()
        ));
    }

    /**
     * Saves a rule
     *
     */
    public function saveRuleAction($validationId)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $validationRepository = $em->getRepository('TeclliureQuestionBundle:Validation');

        $entity = $validationRepository->find($validationId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Validation entity.');
        }

        if ($request->isXmlHttpRequest()) {
            if ($request->get('ruleId')) {
                $rule = $em->getRepository('TeclliureQuestionBundle:ValidationRule')->find($request->get('ruleId'));
            }
            else {
                $rule = new ValidationRule();
            }

            $rule->setValidation($entity);
            $ruleForm = $this->createForm(new ValidationRuleType(), $rule);
            $ruleForm->bind($request);

            if ($ruleForm->isValid()) {
                $em->persist($rule);
                $em->flush();

                $this->get('session')->setFlash('info',
                    'Rule saved correctly'
                );


               $viewParams = array(
                   'validation'      => $entity
               );
            }
            else {
                $viewParams = array(
                    'validation'      => $entity,
                    'ruleForm' => $ruleForm->createView()
                );
            }

            return $this->render(':ajax:base_ajax.html.twig', array_merge(array(
                'template'          => 'TeclliureQuestionBundle:Validation:ruleElement.html.twig'),
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
     * Deletes a ValidationRule
     *
     */
    public function deleteRuleAction($ruleId)
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $ruleRepository = $em->getRepository('TeclliureQuestionBundle:ValidationRule');

            $entity = $ruleRepository->find($ruleId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ValidationRule entity.');
            }
            $validation = $entity->getValidation();

            $em->remove($entity);
            $em->flush();

            $this->get('session')->setFlash('error',
                'Rule DELETED correctly'
            );

            return $this->render(':ajax:base_ajax.html.twig', array(
                'template'      => 'TeclliureQuestionBundle:Validation:ruleList.html.twig',
                'validation'      => $validation
            ));
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }


    /**
     * Saves validation questions
     *
     */
    public function saveValidationQuestionsAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $closeDialog = false;
            $validationRepository = $em->getRepository('TeclliureQuestionBundle:Validation');

            $entity = $validationRepository->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Validation entity.');
            }

            $validationQuestionForm = $this->createForm(new ValidationQuestionsType(), $entity);

            if  ($request->isMethod('post')) {
                $validationQuestionForm->bind($request);

                if ($validationQuestionForm->isValid()) {
                    $em->persist($entity);
                    $em->flush();

                    $this->get('session')->setFlash('info',
                        'Validation questions saved correctly'
                    );
                    $closeDialog = true;
                }
            }

            return $this->render(':ajax:base_ajax.html.twig', array(
                'template'          => 'TeclliureQuestionBundle:Validation:validationQuestions.html.twig',
                'entity'            => $entity,
                'form'              => $validationQuestionForm->createView(),
                'closeDialog'       => $closeDialog
            ));
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }

    /**
     * Get validation questions number
     *
     */
    public function getValidationQuestionsNumberAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $closeDialog = false;
            $validationRepository = $em->getRepository('TeclliureQuestionBundle:Validation');

            $entity = $validationRepository->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Validation entity.');
            }

            return new Response(json_encode(count($entity->getQuestions())));
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }
}
