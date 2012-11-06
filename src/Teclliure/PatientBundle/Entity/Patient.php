<?php

namespace Teclliure\PatientBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="patient")
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 */
class Patient
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     *
     * @Assert\NotBlank()
     * @Assert\Length(min = 5, max = 255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min = 5, max = 20)
     */
    private $identification;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $birthDate;

    /**
     *
     * @ORM\Column(type="text", nullable = TRUE)
     *
     */
    private $notes;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Teclliure\UserBundle\Entity\User")
     *
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $active;

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