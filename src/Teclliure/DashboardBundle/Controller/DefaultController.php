<?php

namespace Teclliure\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as sf2Controller;

class DefaultController extends sf2Controller
{
    public function indexAction()
    {
        return $this->render('TeclliureDashboardBundle:Default:index.html.twig', array());
    }
}
