<?php

namespace Teclliure\QuestionBundle\Constraint;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class RangeMinMaxOverlapsValidator extends ConstraintValidator
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($object, Constraint $constraint)
    {
        $dql = 'SELECT count(vr.id) FROM TeclliureQuestionBundle:ValidationRule vr WHERE vr.id != :id AND vr.validation = :validation AND
        ((:rangeMin >= vr.rangeMin AND :rangeMin <= vr.rangeMax) OR (:rangeMax >= vr.rangeMin AND :rangeMax <= vr.rangeMax))';

        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $object->getId());
        $query->setParameter('validation', $object->getValidation()->getId());
        $query->setParameter('rangeMin', $object->getRangeMin());
        $query->setParameter('rangeMax', $object->getRangeMax());
        $numRows = $query->getSingleScalarResult();

        if ($numRows) {
            $this->context->addViolationAtSubPath('rangeMin', $constraint->message, array('{{ min }}' => $object->getRangeMin(), '{{ max }}' => $object->getRangeMax()));
        }
    }

}
