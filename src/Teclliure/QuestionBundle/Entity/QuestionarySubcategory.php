<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="questionary_subcategory")
 * @ORM\Entity(repositoryClass="Teclliure\QuestionBundle\Entity\QuestionarySubcategoryRepository")
 */
class QuestionarySubcategory
{
    /**
    * @ORM\Id
    * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Questionary",inversedBy="subcategories")
    *
    */
    private $questionary;

    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\CategoryBundle\Entity\Subcategory",inversedBy="questionaries")
     *
     */
    private $subcategory;

    /**
     * Set subcategory
     *
     * @param \Teclliure\CategoryBundle\Entity\Subcategory $subcategory
     * @return QuestionarySubcategory
     */
    public function setSubcategory(\Teclliure\CategoryBundle\Entity\Subcategory $subcategory)
    {
        $this->subcategory = $subcategory;
    
        return $this;
    }

    /**
     * Get subcategory
     *
     * @return \Teclliure\CategoryBundle\Entity\Subcategory 
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * Set questionary
     *
     * @param \Teclliure\QuestionBundle\Entity\Questionary $questionary
     * @return QuestionarySubcategory
     */
    public function setQuestionary(\Teclliure\QuestionBundle\Entity\Questionary $questionary)
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
}