<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Sortable\Entity\Repository\SortableRepository;

class QuestionaryRepository extends SortableRepository
{
    public function queryAll() {
        $em = $this->getEntityManager();

        $dql = 'SELECT q FROM TeclliureQuestionBundle:Questionary q';
        $query = $em->createQuery($dql);
        return $query;
    }

}