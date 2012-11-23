<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
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
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(min = 5, max = 255)
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
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Questionary")
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
}