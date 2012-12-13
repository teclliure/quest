<?php
namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Answer
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
     * @Assert\Length(min = 2, max = 255)
     * @Assert\NotBlank()
     *
     */
    private $answer;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     *
     * @Assert\Length(min = 5, max = 20000)
     *
     */
    private $help;

     /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="integer")
     * @Assert\Range(min = 0)
     */
    protected $rawValue = 0;


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
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Question", inversedBy="answers")
     *
     */
    private $question;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function doOnPrePersist()
    {
        $category = 'question'.$this->getQuestion()->getId();
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
     * Set answer
     *
     * @param string $answer
     * @return Answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    
        return $this;
    }

    /**
     * Get answer
     *
     * @return string 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set help
     *
     * @param string $help
     * @return Answer
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
     * @return Answer
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
     * Set question
     *
     * @param Teclliure\QuestionBundle\Entity\Question $question
     * @return Answer
     */
    public function setQuestion(\Teclliure\QuestionBundle\Entity\Question $question = null)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return Teclliure\QuestionBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set rawValue
     *
     * @param integer $rawValue
     * @return Answer
     */
    public function setRawValue($rawValue)
    {
        $this->rawValue = $rawValue;
    
        return $this;
    }

    /**
     * Get rawValue
     *
     * @return integer 
     */
    public function getRawValue()
    {
        return $this->rawValue;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Answer
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
}