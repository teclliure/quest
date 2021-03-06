<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="questionary")
 * @ORM\Entity(repositoryClass="Teclliure\QuestionBundle\Entity\QuestionaryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Questionary
{
    /**
    *
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    *
    */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 5, max = 255)
     *
     */
    private $name;

    /**
     * @ORM\Column(type="text", length=3000, nullable=true)
     * @Assert\Length(min = 5, max = 3000)
     */
    private $libraryReference;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(min = 5)
     *
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(min = 5)
     *
     */
    private $help;

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
     *
     */
    private $updated;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     *
     */
    private $active = false;

    /**
     *
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\PatientQuestionary", mappedBy="questionary")
     *
     */
    private $patients;

    /**
     *
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\QuestionarySubcategory", mappedBy="questionary")
     *
     */
    private $subcategories;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Teclliure\DocBundle\Entity\Doc", mappedBy="questionaries")
     * @ORM\OrderBy({"name" = "ASC"})
     *
     */
    private $docs;

    /**
     * @ORM\OneToMany(targetEntity="Teclliure\QuestionBundle\Entity\Question", mappedBy="questionary")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $questions;

    /**
     * Get string
     *
     * @return integer
     */
    public function __toString()
    {
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
     * @return Questionary
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
     * Set description
     *
     * @param string $description
     * @return Questionary
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

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Questionary
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
     * @return Questionary
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
     * Set active
     *
     * @param boolean $active
     * @return Questionary
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


    public function doSaveSubcategories($entityManager)
    {
        $questionaryRepository = $entityManager->getRepository('TeclliureQuestionBundle:Questionary');

        if (isset($this->subcategoriesTmp)) {
            $questionaryRepository->deleteSubcategories($this);
            $questionaryRepository->addSubcategories($this, $this->subcategoriesTmp);
        }
    }


    public function doDeleteSubcategories($entityManager)
    {
        $questionaryRepository = $entityManager->getRepository('TeclliureQuestionBundle:Questionary');
        $questionaryRepository->deleteSubcategories($this);
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->patients = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add patients
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $patients
     * @return Questionary
     */
    public function addPatient(\Teclliure\QuestionBundle\Entity\PatientQuestionary $patients)
    {
        $this->patients[] = $patients;
    
        return $this;
    }

    /**
     * Remove patients
     *
     * @param \Teclliure\QuestionBundle\Entity\PatientQuestionary $patients
     */
    public function removePatient(\Teclliure\QuestionBundle\Entity\PatientQuestionary $patients)
    {
        $this->patients->removeElement($patients);
    }

    /**
     * Get patients
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPatients()
    {
        return $this->patients;
    }

    /**
     * Add subcategories
     *
     * @param \Teclliure\QuestionBundle\Entity\QuestionarySubcategory $subcategories
     * @return Questionary
     */
    public function addSubcategorie(\Teclliure\QuestionBundle\Entity\QuestionarySubcategory $subcategories)
    {
        $this->subcategories[] = $subcategories;
    
        return $this;
    }

    /**
     * Remove subcategories
     *
     * @param \Teclliure\QuestionBundle\Entity\QuestionarySubcategory $subcategories
     */
    public function removeSubcategorie(\Teclliure\QuestionBundle\Entity\QuestionarySubcategory $subcategories)
    {
        $this->subcategories->removeElement($subcategories);
    }

    /**
     * Get subcategories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubcategories()
    {
        return $this->subcategories;
    }

    /**
     * Add questions
     *
     * @param \Teclliure\QuestionBundle\Entity\Question $questions
     * @return Questionary
     */
    public function addQuestion(\Teclliure\QuestionBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;
    
        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Teclliure\QuestionBundle\Entity\Question $questions
     */
    public function removeQuestion(\Teclliure\QuestionBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set libraryReference
     *
     * @param string $libraryReference
     * @return Questionary
     */
    public function setLibraryReference($libraryReference)
    {
        $this->libraryReference = $libraryReference;
    
        return $this;
    }

    /**
     * Get libraryReference
     *
     * @return string 
     */
    public function getLibraryReference()
    {
        return $this->libraryReference;
    }

    /**
     * Set help
     *
     * @param string $help
     * @return Questionary
     */
    public function setHelp($help)
    {
        $this->help = $help;
    
        return $this;
    }

    /**
     * Get help
     *
     * @return string 
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * Add docs
     *
     * @param \Teclliure\DocBundle\Entity\Doc $docs
     * @return Questionary
     */
    public function addDoc(\Teclliure\DocBundle\Entity\Doc $docs)
    {
        $this->docs[] = $docs;
    
        return $this;
    }

    /**
     * Remove docs
     *
     * @param \Teclliure\DocBundle\Entity\Doc $docs
     */
    public function removeDoc(\Teclliure\DocBundle\Entity\Doc $docs)
    {
        $this->docs->removeElement($docs);
    }

    /**
     * Get docs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocs()
    {
        return $this->docs;
    }
}