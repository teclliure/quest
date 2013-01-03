<?php

namespace Teclliure\QuestionBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer;

class PatientQuestionaryAnswerToNumberTransformer implements DataTransformerInterface
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
     * Transforms an object (PatientQuestionaryAnswer) to a string (number).
     *
     * @param  PatientQuestionaryAnswer|null $PatientQuestionaryAnswer
     * @return string
     */
    public function transform($PatientQuestionaryAnswer)
    {
        if (null === $patientQuestionaryAnswer) {
            return "";
        }

        return $patientQuestionaryAnswer->getAnswer()->getId();
    }

    /**
     * Transforms a string (number) to an object (PatientQuestionaryAnswer).
     *
     * @param  string $number
     * @return PatientQuestionaryAnswer|null
     * @throws TransformationFailedException if object (PatientQuestionaryAnswer) is not found.
     */
    public function reverseTransform($number)
    {
        if (!$number) {
            return null;
        }

        $atientQuestionaryAnswer = $this->om
            ->getRepository('AcmeTaskBundle:PatientQuestionaryAnswer')
            ->findOneBy(array('number' => $number))
        ;

        if (null === $PatientQuestionaryAnswer) {
            throw new TransformationFailedException(sprintf(
                'An PatientQuestionaryAnswer with number "%s" does not exist!',
                $number
            ));
        }

        return $PatientQuestionaryAnswer;
    }
}