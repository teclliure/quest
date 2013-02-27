<?php

namespace Teclliure\CategoryBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Teclliure\CategoryBundle\Entity\CategoryRepository")
 * @ORM\Table(name="subcategory")
 *
 */
class Subcategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string $email
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable = true)
     *
     * @var string $desc
     */
    private $description;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Teclliure\CategoryBundle\Entity\Category", inversedBy="subcategories")
     *
     */
    private $category;

    /**
     *
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\QuestionarySubcategory", mappedBy="subcategory", cascade={"remove"}, orphanRemoval=true)
     *
     */
    private $questionaries;


    public function __toString()
    {
        return $this->getName();
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
     * @return Subcategory
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
     * Set category
     *
     * @param Teclliure\CategoryBundle\Entity\Category $category
     * @return Subcategory
     *
     */
    public function setCategory(\Teclliure\CategoryBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return Teclliure\CategoryBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Subcategory
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
     * Constructor
     */
    public function __construct()
    {
        $this->questionaries = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add questionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\QuestionarySubcategory $questionaries
     * @return Subcategory
     */
    public function addQuestionarie(\Teclliure\QuestionBundle\Entity\QuestionarySubcategory $questionaries)
    {
        $this->questionaries[] = $questionaries;
    
        return $this;
    }

    /**
     * Remove questionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\QuestionarySubcategory $questionaries
     */
    public function removeQuestionarie(\Teclliure\QuestionBundle\Entity\QuestionarySubcategory $questionaries)
    {
        $this->questionaries->removeElement($questionaries);
    }

    /**
     * Get questionaries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestionaries()
    {
        return $this->questionaries;
    }
}