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
            FROM Category
            WHERE c.active = 1
            ORDER BY c.name asc
        ');

        return $query->getResult();
    }
}
