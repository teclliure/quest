<?php

namespace Teclliure\CategoryBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Teclliure\CategoryBundle\Entity\CategoryRepository")
 * @ORM\Table(name="category")
 *
 */
class Category
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
     * @Assert\NotBlank()
     * @Assert\Length(min = 5, max = 255)
     *
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable = true)
     *
     * @Assert\Length(min = 5, max = 255)
     *
     * @var string $desc
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $is_required;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $is_multiple;


    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     *
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    public function __toString() {
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
     * @return Category
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
     * Set active
     *
     * @param boolean $active
     * @return Category
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set is_required
     *
     * @param boolean $isRequired
     * @return Category
     */
    public function setIsRequired($isRequired)
    {
        $this->is_required = $isRequired;
    
        return $this;
    }

    /**
     * Get is_required
     *
     * @return boolean 
     */
    public function getIsRequired()
    {
        return $this->is_required;
    }

    /**
     * Set is_multiple
     *
     * @param boolean $isMultiple
     * @return Category
     */
    public function setIsMultiple($isMultiple)
    {
        $this->is_multiple = $isMultiple;
    
        return $this;
    }

    /**
     * Get is_multiple
     *
     * @return boolean 
     */
    public function getIsMultiple()
    {
        return $this->is_multiple;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Category
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
     * @return Category
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
     * Set description
     *
     * @param string $description
     * @return Category
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
}