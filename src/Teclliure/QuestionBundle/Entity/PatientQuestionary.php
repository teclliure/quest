<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="patient_questionary")
 */
class PatientQuestionary
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Questionary")
     *
     */
    private $questionary;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Teclliure\PatientBundle\Entity\Patient")
     *
     */
    private $patient;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     *
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     *
     * @ORM\Column(type="text", nullable = TRUE)
     *
     * @Assert\Length(min = 5, max = 2000)
     *
     */
    private $notes;

}
