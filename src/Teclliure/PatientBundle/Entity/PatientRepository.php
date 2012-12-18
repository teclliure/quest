<?php

namespace Teclliure\PatientBundle\Entity;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use \Teclliure\CategoryBundle\Entity\Subcategory;
use Doctrine\Common\Collections\ArrayCollection;

class PatientRepository extends SortableRepository
{
    public function queryAllFromUser($userId, $searchString = null) {
        $em = $this->getEntityManager();

        $dql = 'SELECT p FROM TeclliurePatientBundle:Patient p where p.user = :user';
        if ($searchString) {
            $dql .= ' AND p.name LIKE :search';
        }
        $dql .= ' ORDER BY p.name';
        $query = $em->createQuery($dql);

        $query->setParameter('user', $userId);
        if ($searchString) {
            $query->setParameter('search', '%'.$searchString.'%');
        }
        return $query;
    }
}