<?php

namespace Teclliure\DashboardBundle\Controller;

use Teclliure\DashboardBundle\Controller\Controller as sf2Controller;

class DefaultController extends sf2Controller
{
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

    public function getBreadcrumbsRoutes() {
        return array(
            'list' => array('route'=>'home', 'label'=>'List patients'),
            'show' => array('route'=>'patient_show', 'label'=>'Patient Show'),
            'new' => array('route'=>'patient_new', 'label'=>'Patient Create'),
            'edit' => array('route'=>'patient_edit', 'label'=>'Patient Edit'),
        );
    }
}
