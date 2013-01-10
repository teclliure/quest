<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="patient_questionary")
 * @ORM\Entity(repositoryClass="Teclliure\QuestionBundle\Entity\PatientQuestionaryRepository")
 */
class PatientQuestionary
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Questionary",inversedBy="patients")
     *
     */
    private $questionary;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Teclliure\PatientBundle\Entity\Patient",inversedBy="questionaries")
     *
     */
    private $patient;

    /**
     *
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer",mappedBy="patientQuestionary")
     *
     */
    private $patientQuestionaryAnswers;

    /**
     *
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation",mappedBy="patientQuestionary",cascade={"persist"})
     *
     */
    private $validations;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     *
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     *
     * @ORM\Column(type="text", nullable = TRUE)
     *
     * @Assert\Length(min = 5, max = 2000)
     *
     */
    private $notes;

    public function __construct()
    {
        $this->validations = new ArrayCollection();
        $this->patientQuestionaryAnswers = new ArrayCollection();
    }

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
     * Set created
     *
     * @param \DateTime $created
     * @return PatientQuestionary
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return PatientQuestionary
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return PatientQuestionary
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    
        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set questionary
     *
     * @param \Teclliure\QuestionBundle\Entity\Questionary $questionary
     * @return PatientQuestionary
     */
    public function setQuestionary(\Teclliure\QuestionBundle\Entity\Questionary $questionary = null)
    {
        $this->questionary = $questionary;
    
        return $this;
    }

    /**
     * Get questionary
     *
     * @return \Teclliure\QuestionBundle\Entity\Questionary 
     */
    public function getQuestionary()
    {
        return $this->questionary;
    }

    /**
     * Set patient
     *
     * @param \Teclliure\PatientBundle\Entity\Patient $patient
     * @return PatientQuestionary
     */
    public function setPatient(\Teclliure\PatientBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;
    
        return $this;
    }

    /**
     * Get patient
     *
     * @return \Teclliure\PatientBundle\Entity\Patient 
     */
    public function getPatient()
    {
        return $this->patient;
    }

    public function doSaveAnswers($entityManager)
    {
        $patientQuestionaryRepository = $entityManager->getRepository('TeclliureQuestionBundle:PatientQuestionary');

        if (isset($this->answersTmp)) {
            $patientQuestionaryRepository->deleteAnswers($this);
            $patientQuestionaryRepository->addAnswers($this, $this->answersTmp);
        }
    }


    public function doDeleteAnswers($entityManager)
    {
        $questionaryRepository = $entityManager->getRepository('TeclliureQuestionBundle:PatientQuestionary');
        $questionaryRepository->deleteAnswers($this);
    }

    /**
     * Add patientQuestionaryAnswers
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer $patientQuestionaryAnswers
     * @return PatientQuestionary
     */
    public function addPatientQuestionaryAnswer(\Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer $patientQuestionaryAnswers)
    {
        $this->patientQuestionaryAnswers[] = $patientQuestionaryAnswers;
    
        return $this;
    }

    /**
     * Remove patientQuestionaryAnswers
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer $patientQuestionaryAnswers
     */
    public function removePatientQuestionaryAnswer(\Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer $patientQuestionaryAnswers)
    {
        $this->patientQuestionaryAnswers->removeElement($patientQuestionaryAnswers);
    }

    /**
     * Get patientQuestionaryAnswers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPatientQuestionaryAnswers()
    {
        return $this->patientQuestionaryAnswers;
    }

    /**
     * Add validations
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation $validations
     * @return PatientQuestionary
     */
    public function addValidation(\Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation $validations)
    {
        $this->validations[] = $validations;
    
        return $this;
    }

    /**
     * Remove validations
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation $validations
     */
    public function removeValidation(\Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation $validations)
    {
        $this->validations->removeElement($validations);
    }

    /**
     * Get validations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValidations()
    {
        return $this->validations;
    }

    /**
     * Set validations
     *
     * @param \Doctrine\Common\Collections\Collection $validations
     */
    public function setValidations(\Doctrine\Common\Collections\ArrayCollection $validations)
    {
        foreach ($this->getValidations() as $validation) {
            $this->removeValidation($validation);
        }

        foreach ($validations as $validation) {
            $validation->setPatientQuestionary($this);
            $this->addValidation($validation);
        }
        return $this->validations;
    }
}