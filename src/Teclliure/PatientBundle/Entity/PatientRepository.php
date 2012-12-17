<?php

namespace Teclliure\PatientBundle\Entity;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use \Teclliure\CategoryBundle\Entity\Subcategory;
use Doctrine\Common\Collections\ArrayCollection;

class PatientRepository extends SortableRepository
{
    public function queryAllFromUser($userId) {
        $em = $this->getEntityManager();

        $dql = 'SELECT p FROM TeclliurePatientBundle:Patient p where p.user = :user ORDER BY p.name';
        $query = $em->createQuery($dql);
        $query->setParameter('user', $userId);

        return $query;
    }
}