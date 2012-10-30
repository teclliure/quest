<?php

namespace Teclliure\Bundle\QuestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TeclliureQuestionBundle:Default:index.html.twig', array('name' => $name));
    }
}
