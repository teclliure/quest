<?php
namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
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
     */
    private $answer;

    /**
     * @ORM\Column(type="text")
     *
     */
    private $help;

     /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="integer")
     * @Assert\Range(min = 0)
     */
    protected $rawValue;


    /**
     * @Gedmo\SortablePosition
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Question")
     *
     */
    private $question;



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
}