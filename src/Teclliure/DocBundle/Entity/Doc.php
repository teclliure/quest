<?php

namespace Teclliure\DocBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ExecutionContext;

/*
 *  TODO:
 *  - Filter by file type (only pdf and images)
 *  - Add subcategory selector
 */

/**
 * @ORM\Table(name="doc")
 * @ORM\Entity(repositoryClass="Teclliure\DocBundle\Entity\DocRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @Assert\Callback(methods={"checkValidateFile"})
 */
class Doc
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
     *
     *
     */
    protected $name;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    protected $path;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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
     * @ORM\ManyToMany(targetEntity="Teclliure\QuestionBundle\Entity\Questionary", cascade={"persist"}, inversedBy="docs", orphanRemoval="true")
     * @ORM\JoinTable(name="doc_questionary")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $questionaries;

    /**
     * @Assert\File(
     *      maxSize="6000000",
     *      mimeTypes = {"application/pdf", "application/x-pdf"},
     *      mimeTypesMessage = "Please upload a valid PDF"
     * )
     *
     */
    protected $file;

    public function getAbsolutePath()
    {
        return null === $this->name ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->name ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            if ($this->getPath()) {
                unlink($this->getAbsolutePath());
            }
            $this->setName($this->file->getClientOriginalName());
            $this->setPath(sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension());
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the target filename to move to
        $this->file->move($this->getUploadRootDir(), $this->getPath());

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->filenameForRemove) {
            unlink($this->filenameForRemove);
        }
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subcategories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Doc
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
     * Set path
     *
     * @param string $path
     * @return Doc
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Doc
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
     * @return Doc
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
     * @return Doc
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
     * @return Doc
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

    public function getFile() {
        return $this->file;
    }

    public function setFile($file) {
        return $this->file = $file;
    }

    /**
     * Add questionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\Questionary $questionaries
     * @return Doc
     */
    public function addQuestionarie(\Teclliure\QuestionBundle\Entity\Questionary $questionaries)
    {
        $this->questionaries[] = $questionaries;
    
        return $this;
    }

    /**
     * Remove questionaries
     *
     * @param \Teclliure\QuestionBundle\Entity\Questionary $questionaries
     */
    public function removeQuestionarie(\Teclliure\QuestionBundle\Entity\Questionary $questionaries)
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
     * Set questionaries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setQuestionaries($questionaries)
    {
        $this->questionaries = $questionaries;
    }

    public function checkValidateFile(ExecutionContext $ec) {
        if ($this->getFile() == '' && $this->getName() == '') {
            $ec->addViolationAtSubPath('file', 'You should upload a file', array(), null);
        }
    }
}