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
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string $email
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable = true)
     *
     * @var string $desc
     */
    private $desc;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Teclliure\CategoryBundle\Entity\Category")
     *
     */
    private $category;

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
     * Set desc
     *
     * @param string $desc
     * @return Subcategory
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    
        return $this;
    }

    /**
     * Get desc
     *
     * @return string 
     */
    public function getDesc()
    {
        return $this->desc;
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
}