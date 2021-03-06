<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Question
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    private $id;

    /**
     * @ORM\Column(type="string", length=600)
     *
     * @Assert\Length(min = 5, max = 600)
     * @Assert\NotBlank()
     *
     */
    private $question;

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
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Questionary", inversedBy="questions")
     *
     */
    protected $questionary;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\QuestionGroup")
     *
     */
    protected $questionGroup;

    /**
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\Answer", mappedBy="question")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $answers;

    /**
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer", mappedBy="question")
     */
    private $patientsQuestionAnswers;

    /*
     * Not persisted variable used to show patient questionaries
     */
    private $patientQuestionAnswer = null;


    /**
     * @ORM\ManyToMany(targetEntity="Teclliure\QuestionBundle\Entity\Answer", cascade={"persist"}, mappedBy="disabledQuestions")
     */
    private $disabledByAnswers;

    /**
     * @ORM\ManyToMany(targetEntity="Teclliure\QuestionBundle\Entity\Validation", mappedBy="questions")
     */
    private $validations;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function doOnPrePersist()
    {
        $category = 'questionary'.$this->getQuestionary()->getId();
        if ($this->getQuestionGroup()) {
            $category .= $category.'questionaryGroup'.$this->getQuestionGroup()->getId();
        }
        $this->setCategory($category);
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
     * Set question
     *
     * @param string $question
     * @return Question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set help
     *
     * @param string $help
     * @return Question
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
     * Set salt
     *
     * @param string $salt
     * @return Question
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Question
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
     * @return Question
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
     * @param Teclliure\QuestionBundle\Entity\Questionary $questionary
     * @return Question
     */
    public function setQuestionary(\Teclliure\QuestionBundle\Entity\Questionary $questionary = null)
    {
        $this->questionary = $questionary;
    
        return $this;
    }

    /**
     * Get questionary
     *
     * @return Teclliure\QuestionBundle\Entity\Questionary 
     */
    public function getQuestionary()
    {
        return $this->questionary;
    }

    /**
     * Set questionGroup
     *
     * @param Teclliure\QuestionBundle\Entity\QuestionGroup $questionGroup
     * @return Question
     */
    public function setQuestionGroup(\Teclliure\QuestionBundle\Entity\QuestionGroup $questionGroup = null)
    {
        $this->questionGroup = $questionGroup;
    
        return $this;
    }

    /**
     * Get questionGroup
     *
     * @return Teclliure\QuestionBundle\Entity\QuestionGroup 
     */
    public function getQuestionGroup()
    {
        return $this->questionGroup;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    }
   

    /**
     * Add answers
     *
     * @param \Teclliure\QuestionBundle\Entity\Answer $answers
     * @return Question
     */
    public function addAnswer(\Teclliure\QuestionBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;
    
        return $this;
    }

    /**
     * Remove answers
     *
     * @param \Teclliure\QuestionBundle\Entity\Answer $answers
     */
    public function removeAnswer(\Teclliure\QuestionBundle\Entity\Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set patientQuestionAnswer
     *
     * @param Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer $patientQuestionAnswer
     * @return PatientQuestionAnswer
     */
    public function setPatientQuestionAnswer(\Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer $patientQuestionAnswer = null)
    {
        $this->patientQuestionAnswer = $patientQuestionAnswer;

        return $this;
    }

    /**
     * Get patientQuestionAnswer
     *
     * @return Teclliure\QuestionBundle\Entity\PatientQuestionAnswer
     */
    public function getPatientQuestionAnswer()
    {
        return $this->patientQuestionAnswer;
    }

    /**
     * Add patientsQuestionAnswers
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer $patientsQuestionAnswers
     * @return Question
     */
    public function addPatientsQuestionAnswer(\Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer $patientsQuestionAnswers)
    {
        $this->patientsQuestionAnswers[] = $patientsQuestionAnswers;
    
        return $this;
    }

    /**
     * Remove patientsQuestionAnswers
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer $patientsQuestionAnswers
     */
    public function removePatientsQuestionAnswer(\Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer $patientsQuestionAnswers)
    {
        $this->patientsQuestionAnswers->removeElement($patientsQuestionAnswers);
    }

    /**
     * Get patientsQuestionAnswers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPatientsQuestionAnswers()
    {
        return $this->patientsQuestionAnswers;
    }

    /**
     * Add disabledByAnswers
     *
     * @param \Teclliure\QuestionBundle\Entity\Answer $disabledByAnswers
     * @return Question
     */
    public function addDisabledByAnswer(\Teclliure\QuestionBundle\Entity\Answer $disabledByAnswers)
    {
        $this->disabledByAnswers[] = $disabledByAnswers;
    
        return $this;
    }

    /**
     * Remove disabledByAnswers
     *
     * @param \Teclliure\QuestionBundle\Entity\Answer $disabledByAnswers
     */
    public function removeDisabledByAnswer(\Teclliure\QuestionBundle\Entity\Answer $disabledByAnswers)
    {
        $this->disabledByAnswers->removeElement($disabledByAnswers);
    }

    /**
     * Get disabledByAnswers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDisabledByAnswers()
    {
        return $this->disabledByAnswers;
    }

    /**
     * Add validations
     *
     * @param \Teclliure\QuestionBundle\Entity\Validation $validations
     * @return Question
     */
    public function addValidation(\Teclliure\QuestionBundle\Entity\Validation $validations)
    {
        $this->validations[] = $validations;
    
        return $this;
    }

    /**
     * Remove validations
     *
     * @param \Teclliure\QuestionBundle\Entity\Validation $validations
     */
    public function removeValidation(\Teclliure\QuestionBundle\Entity\Validation $validations)
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

    public function getQuestionHelp() {
        return $this->getQuestion().' - '.$this->getHelp();
    }
}