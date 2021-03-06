<?php
namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="report")
 * @ORM\Entity(repositoryClass="Teclliure\QuestionBundle\Entity\ReportRepository")
 */
class Report
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Teclliure\PatientBundle\Entity\Patient",inversedBy="reports")
     *
     */
    private $patient;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(min = 2, max = 255)
     * @Assert\NotBlank()
     *
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     *
     * @Assert\Length(min = 5, max = 20000)
     *
     */
    private $description;

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
     * @ORM\ManyToMany(targetEntity="Teclliure\QuestionBundle\Entity\PatientQuestionary", cascade={"persist"}, inversedBy="reports")
     * @ORM\JoinTable(name="report_patient_questionary")
     */
    private $patientQuestionaries;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->validations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Report
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Report
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add patientQuestionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionaries
     * @return Report
     */
    public function addPatientQuestionary(\Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionaries)
    {
        $this->patientQuestionaries[] = $patientQuestionaries;
    
        return $this;
    }

    /**
     * Remove patientQuestionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionaries
     */
    public function removePatientQuestionary(\Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionaries)
    {
        $this->patientQuestionaries->removeElement($patientQuestionaries);
    }

    /**
     * Get patientQuestionaries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPatientQuestionaries()
    {
        return $this->patientQuestionaries;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Report
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
     * @return Report
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
     * Set patient
     *
     * @param \Teclliure\PatientBundle\Entity\Patient $patient
     * @return Report
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

    /**
     * Add patientQuestionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionaries
     * @return Report
     */
    public function addPatientQuestionarie(\Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionaries)
    {
        $this->patientQuestionaries[] = $patientQuestionaries;
    
        return $this;
    }

    /**
     * Remove patientQuestionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionaries
     */
    public function removePatientQuestionarie(\Teclliure\QuestionBundle\Entity\PatientQuestionary $patientQuestionaries)
    {
        $this->patientQuestionaries->removeElement($patientQuestionaries);
    }
}