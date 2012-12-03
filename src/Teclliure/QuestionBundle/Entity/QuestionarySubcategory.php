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
    * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Question")
    *
    */
    private $question;

    /**
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\CategoryBundle\Entity\Subcategory")
     *
     */
    private $subcategory;
}