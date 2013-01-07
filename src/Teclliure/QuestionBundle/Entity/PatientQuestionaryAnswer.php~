<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="patient_questionary_answer")
 * @ORM\Entity
 */
class PatientQuestionaryAnswer
{
    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\PatientQuestionary", inversedBy="patientQuestionaryAnswers")
     *
     */
    private $patientQuestionary;

    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Question",inversedBy="patientsQuestionAnswers")
     *
     */
    private $question;

    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Answer")
     *
     */
    private $answer;


    /**
     * Set answer
     *
     * @param \Teclliure\QuestionBundle\Entity\Answer $answer
     * @return PatientQuestionaryAnswer
     */
    public function setAnswer(\Teclliure\QuestionBundle\Entity\Answer $answer = null)
    {
        $this->answer = $answer;
    
        return $this;
    }

    /**
     * Get answer
     *
     * @return \Teclliure\QuestionBundle\Entity\Answer 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set question
     *
     * @param \Teclliure\QuestionBundle\Entity\Question $question
     * @return PatientQuestionaryAnswer
     */
    public function setQuestion(\Teclliure\QuestionBundle\Entity\Question $question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return \Teclliure\QuestionBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set patientQuestionary
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionary
     * @return PatientQuestionaryAnswer
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
}