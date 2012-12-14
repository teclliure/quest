<?php

namespace Teclliure\QuestionBundle\Service;

use Doctrine\ORM\EntityManager;
use Teclliure\QuestionBundle\Entity\Validation;

class QuestionManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadValidationsWithErrorsAndWarnings(Validation $validation) {

    }
}
