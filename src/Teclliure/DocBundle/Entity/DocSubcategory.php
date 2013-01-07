<?php

namespace Teclliure\DocBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="doc_subcategory")
 * @ORM\Entity(repositoryClass="Teclliure\DocBundle\Entity\DocSubcategoryRepository")
 */
class DocSubcategory
{
    /**
    * @ORM\Id
    * @ORM\ManyToOne(targetEntity="Teclliure\DocBundle\Entity\Doc",inversedBy="subcategories")
    *
    */
    private $doc;

    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\CategoryBundle\Entity\Subcategory", inversedBy="docs")
     *
     */
    private $subcategory;

    /**
     * Set doc
     *
     * @param \Teclliure\DocBundle\Entity\Doc $doc
     * @return DocSubcategory
     */
    public function setDoc(\Teclliure\DocBundle\Entity\Doc $doc)
    {
        $this->doc = $doc;
    
        return $this;
    }

    /**
     * Get doc
     *
     * @return \Teclliure\DocBundle\Entity\Doc 
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * Set subcategory
     *
     * @param \Teclliure\CategoryBundle\Entity\Subcategory $subcategory
     * @return DocSubcategory
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
}