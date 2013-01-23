<?php

namespace Teclliure\PatientBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="patient")
 * @ORM\Entity(repositoryClass="Teclliure\PatientBundle\Entity\PatientRepository")
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
     *
     * @ORM\Column(type="text", nullable = TRUE)
     *
     * @Assert\Length(min = 5, max = 1000)
     *
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Email()
     *
     * @var string $email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     *
     * @Assert\Length(min = 8, max = 20)
     *
     * @var string $phone
     */
    private $phone;

    /**
     * @ORM\Column(type="date", nullable = TRUE)
     *
     * @Assert\Date
     */
    private $birthDate;

    /**
     *
     * @ORM\Column(type="text", nullable = TRUE)
     *
     * @Assert\Length(min = 5, max = 2000)
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
    private $active = true;

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

    /**
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\PatientQuestionary", mappedBy="patient")
     */
    private $questionaries;

    /**
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\Report", mappedBy="patient")
     */
    private $reports;

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
     * @return Patient
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
     * Set identification
     *
     * @param string $identification
     * @return Patient
     */
    public function setIdentification($identification)
    {
        $this->identification = $identification;
    
        return $this;
    }

    /**
     * Get identification
     *
     * @return string 
     */
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return Patient
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    
        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime 
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Patient
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    
        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Patient
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
     * Set created
     *
     * @param \DateTime $created
     * @return Patient
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
     * @return Patient
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
     * Set user
     *
     * @param Teclliure\UserBundle\Entity\User $user
     * @return Patient
     */
    public function setUser(\Teclliure\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return Teclliure\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Patient
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Patient
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Patient
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questionaries = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add questionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $questionaries
     * @return Patient
     */
    public function addQuestionarie(\Teclliure\QuestionBundle\Entity\PatientQuestionary $questionaries)
    {
        $this->questionaries[] = $questionaries;
    
        return $this;
    }

    /**
     * Remove questionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $questionaries
     */
    public function removeQuestionarie(\Teclliure\QuestionBundle\Entity\PatientQuestionary $questionaries)
    {
        $this->questionaries->removeElement($questionaries);
    }

    /**
     * Get questionaries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestionaries()
    {
        return $this->questionaries;
    }

    /**
     * Add reports
     *
     * @param \Teclliure\QuestionBundle\Entity\Report $reports
     * @return Patient
     */
    public function addReport(\Teclliure\QuestionBundle\Entity\Report $reports)
    {
        $this->reports[] = $reports;
    
        return $this;
    }

    /**
     * Remove reports
     *
     * @param \Teclliure\QuestionBundle\Entity\Report $reports
     */
    public function removeReport(\Teclliure\QuestionBundle\Entity\Report $reports)
    {
        $this->reports->removeElement($reports);
    }

    /**
     * Get reports
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReports()
    {
        return $this->reports;
    }
}