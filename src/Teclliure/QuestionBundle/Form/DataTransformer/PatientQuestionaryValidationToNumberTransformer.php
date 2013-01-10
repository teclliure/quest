<?php

namespace Teclliure\QuestionBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation;
use Doctrine\Common\Collections\ArrayCollection;

class PatientQuestionaryValidationToNumberTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms a PatientQuestionaryValidation collection to Validation collection.
     *
     * @param ArrayCollection|null $patientQuestionaryValidation
     *
     * @return ArrayCollection Validation
     */
    public function transform($patientQuestionaryValidations)
    {
        $results = new ArrayCollection();
        if (null === $patientQuestionaryValidations) {
            return $results;
        }

        foreach ($patientQuestionaryValidations as $patientQuestionaryValidation) {
            $results->add($patientQuestionaryValidation->getValidation());
        }

        return $results;
    }

    /**
     * Transforms a ArrayCollection (Validations) to an ArrayCollection (PatientQuestionaryValidation).
     *
     * @param  ArrayCollection $validations
     * @return PatientQuestionaryValidation|null
     * @throws TransformationFailedException if object (validation) is not found.
     */
    public function reverseTransform($validations)
    {
        $patientQuestionaryValidations = new ArrayCollection();
        if (!$validations || !count($validations)) {
            return $patientQuestionaryValidations;
        }


        foreach ($validations as $validation) {
            $patientQuestionaryValidation = new PatientQuestionaryValidation();
            $patientQuestionaryValidation->setValidation($validation);
            $patientQuestionaryValidations->add($patientQuestionaryValidation);
        }


        return $patientQuestionaryValidations;
    }
}