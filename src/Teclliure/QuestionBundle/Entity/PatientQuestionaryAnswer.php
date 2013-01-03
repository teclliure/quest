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
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\PatientQuestionary",inversedBy="patientQuestionaryAnswers")
     *
     */
    private $patientQuestionary;

    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Question")
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set patient_questionary
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionary
     * @return PatientQuestionaryAnswer
     */
    public function setPatientQuestionary(\Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionary = null)
    {
        $this->patient_questionary = $patientQuestionary;

        return $this;
    }

    /**
     * Get patient_questionary
     *
     * @return \Teclliure\QuestionBundle\Entity\PatientQuestionary
     */
    public function getPatientQuestionary()
    {
        return $this->patient_questionary;
    }

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
}