<?php

namespace Teclliure\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findActiveUsers()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT u
            FROM User
            WHERE u.active = 1
            ORDER BY v.fecha DESC
        ');

        return $query->getResult();
    }
}
