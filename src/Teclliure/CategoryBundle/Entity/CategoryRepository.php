<?php

namespace Teclliure\CategoryBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findActive()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT c
            FROM TeclliureCategoryBundle:Category c
            WHERE c.active = 1
            ORDER BY c.name asc
        ');

        return $query->getResult();
    }
}
