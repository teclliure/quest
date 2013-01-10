<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="validation")
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Validation
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(min = 5, max = 255)
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
    private $help;

    /**
    * @Gedmo\SortablePosition
    *
    * @ORM\Column(name="position", type="integer")
    */
    private $position;

    /**
    * @Gedmo\SortableGroup
     *
    * @ORM\Column(name="category", type="string", length=128)
    */
    private $category = 'default';

    /**
     *
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Questionary")
     *
     */
    protected $questionary;


    /**
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\ValidationRule", mappedBy="validation")
     * @ORM\OrderBy({"rangeMin" = "ASC"})
     */
    private $validationRules;

    /**
     *
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation",mappedBy="validation")
     *
     */
    private $patientQuestionaries;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function doOnPrePersist()
    {
        $category = 'questionary'.$this->getQuestionary()->getId();
        $this->setCategory($category);
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->validationRules = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Validation
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
     * Set help
     *
     * @param string $help
     * @return Validation
     */
    public function setHelp($help)
    {
        $this->help = $help;
    
        return $this;
    }

    /**
     * Get help
     *
     * @return string 
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Validation
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Validation
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set questionary
     *
     * @param \Teclliure\QuestionBundle\Entity\Questionary $questionary
     * @return Validation
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
     * Add validationRules
     *
     * @param \Teclliure\QuestionBundle\Entity\ValidationRule $validationRules
     * @return Validation
     */
    public function addValidationRule(\Teclliure\QuestionBundle\Entity\ValidationRule $validationRules)
    {
        $this->validationRules[] = $validationRules;
    
        return $this;
    }

    /**
     * Remove validationRules
     *
     * @param \Teclliure\QuestionBundle\Entity\ValidationRule $validationRules
     */
    public function removeValidationRule(\Teclliure\QuestionBundle\Entity\ValidationRule $validationRules)
    {
        $this->validationRules->removeElement($validationRules);
    }

    /**
     * Get validationRules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }

    /**
     * Add patientQuestionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation $patientQuestionaries
     * @return Validation
     */
    public function addPatientQuestionarie(\Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation $patientQuestionaries)
    {
        $this->patientQuestionaries[] = $patientQuestionaries;
    
        return $this;
    }

    /**
     * Remove patientQuestionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation $patientQuestionaries
     */
    public function removePatientQuestionarie(\Teclliure\QuestionBundle\Entity\PatientQuestionaryValidation $patientQuestionaries)
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
}