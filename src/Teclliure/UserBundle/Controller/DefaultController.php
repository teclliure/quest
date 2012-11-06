<?php

namespace Teclliure\UserBundle\Controller;

use Teclliure\UserBundle\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TeclliureUserBundle:Default:index.html.twig', array('name' => 'Quest'));
    }
}
