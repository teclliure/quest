<?php

namespace Teclliure\QuestionBundle\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RangeMinMaxOverlaps extends Constraint
{
    public $message = 'The range {{ min }} - {{ max }} overlaps with another previously entered range.';

    public function validatedBy()
    {
        return 'alias_range_min_max_constraint';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
