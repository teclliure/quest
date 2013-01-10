<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="patient_questionary_validation")
 * @ORM\Entity
 */
class PatientQuestionaryValidation
{
    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\PatientQuestionary",inversedBy="validations")
     *
     */
    private $patientQuestionary;

    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Validation",inversedBy="patientQuestionaries",cascade={"persist"})
     *
     */
    private $validation;

/*    public function __toString() {
        $string = '';
        if ($this->getPatientQuestionary()) {
            $string .= $this->getPatientQuestionary()->getPatient();
            $string .= $this->getPatientQuestionary()->getQuestionary();
        }
        $string .= $this->getValidation();

        return $string;
    }*/

    /**
     * Set patientQuestionary
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionary
     * @return PatientQuestionaryValidation
     */
    public function setPatientQuestionary(\Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionary)
    {
        $this->patientQuestionary = $patientQuestionary;
    
        return $this;
    }

    /**
     * Get patientQuestionary
     *
     * @return \Teclliure\QuestionBundle\Entity\PatientQuestionary 
     */
    public function getPatientQuestionary()
    {
        return $this->patientQuestionary;
    }

    /**
     * Set validation
     *
     * @param \Teclliure\QuestionBundle\Entity\Validation $validation
     * @return PatientQuestionaryValidation
     */
    public function setValidation(\Teclliure\QuestionBundle\Entity\Validation $validation)
    {
        $this->validation = $validation;
    
        return $this;
    }

    /**
     * Get validation
     *
     * @return \Teclliure\QuestionBundle\Entity\Validation 
     */
    public function getValidation()
    {
        return $this->validation;
    }
}