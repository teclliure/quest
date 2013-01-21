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
            'list' => array('route'=>'home', 'label'=>'List persons'),
            'show' => array('route'=>'patient_show', 'label'=>'Person Show'),
            'new' => array('route'=>'patient_new', 'label'=>'Person Create'),
            'edit' => array('route'=>'patient_edit', 'label'=>'Person Edit'),
        );
    }
}
