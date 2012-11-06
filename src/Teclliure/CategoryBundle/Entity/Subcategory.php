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
}