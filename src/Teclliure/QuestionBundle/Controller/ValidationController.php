<?php

namespace Teclliure\QuestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Teclliure\QuestionBundle\Entity\Validation;
use Teclliure\QuestionBundle\Entity\ValidationRule;
use Teclliure\QuestionBundle\Form\ValidationType;
use Teclliure\QuestionBundle\Form\ValidationRuleType;

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
     * Saves a validation
     *
     */
    public function saveValidationAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $questionary = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $entity = $questionary->find($id);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        if ($request->isXmlHttpRequest()) {
            if ($request->get('validationId')) {
                $validation = $em->getRepository('TeclliureQuestionBundle:Validation')->find($request->get('validationId'));
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


            $validations = $validationary->findValidations($entity);

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
    public function formRuleAction($validationId)
    {
        $em = $this->getDoctrine()->getManager();

        $validationRepository = $em->getRepository('TeclliureQuestionBundle:Validation');

        $entity = $validationRepository->find($validationId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Validation entity.');
        }

        $ruleForm = $this->createForm(new ValidationRuleType(), new ValidationRule());

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
}
