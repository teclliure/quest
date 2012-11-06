<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="question_group")
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 */
class QuestionGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(type="string", length=100) */
    private $name;

    /** @ORM\Column(type="text") */
    private $help;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /** @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Questionary") */
    protected $questionary;


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
     * @return QuestionGroup
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
     * @return QuestionGroup
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
     * @return QuestionGroup
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
     * Set questionary
     *
     * @param Teclliure\QuestionBundle\Entity\Questionary $questionary
     * @return QuestionGroup
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
}