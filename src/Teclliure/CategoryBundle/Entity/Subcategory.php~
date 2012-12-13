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
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\QuestionarySubcategory", inversedBy="subcategory")
     * @ORM\JoinColumn(name="id", referencedColumnName="subcategory_id")
     *
     */
    private $questionarySubcategory;

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
     * Set questionaryCategory
     *
     * @param \Teclliure\QuestionBundle\Entity\QuestionarySubcategory $questionaryCategory
     * @return Subcategory
     */
    public function setQuestionaryCategory(\Teclliure\QuestionBundle\Entity\QuestionarySubcategory $questionaryCategory = null)
    {
        $this->questionaryCategory = $questionaryCategory;
    
        return $this;
    }

    /**
     * Get questionaryCategory
     *
     * @return \Teclliure\QuestionBundle\Entity\QuestionarySubcategory 
     */
    public function getQuestionaryCategory()
    {
        return $this->questionaryCategory;
    }

    /**
     * Set questionarySubcategory
     *
     * @param \Teclliure\QuestionBundle\Entity\QuestionarySubcategory $questionarySubcategory
     * @return Subcategory
     */
    public function setQuestionarySubcategory(\Teclliure\QuestionBundle\Entity\QuestionarySubcategory $questionarySubcategory = null)
    {
        $this->questionarySubcategory = $questionarySubcategory;
    
        return $this;
    }

    /**
     * Get questionarySubcategory
     *
     * @return \Teclliure\QuestionBundle\Entity\QuestionarySubcategory 
     */
    public function getQuestionarySubcategory()
    {
        return $this->questionarySubcategory;
    }
}