<?php

namespace Teclliure\CategoryBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

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
}