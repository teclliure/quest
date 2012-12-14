<?php
namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Teclliure\QuestionBundle\Constraint as QuestionAssert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Table(name="validation_rule")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Assert\Callback(methods={
 *      "isMinMaxValid"
 * })
 * @QuestionAssert\RangeMinMaxOverlaps
 *
 */
class ValidationRule
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
     * @Assert\Length(min = 2, max = 255)
     * @Assert\NotBlank()
     *
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     *
     * @Assert\Length(min = 5, max = 20000)
     *
     */
    private $help;

     /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="integer")
     * @Assert\Range(min = 0)
     */
    protected $rangeMin;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="integer")
     * @Assert\Range(min = 0)
     */
    protected $rangeMax;

    /**
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Validation", inversedBy="validationRules")
     *
     */
    private $validation;


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
     * @return ValidationRule
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
     * Set help
     *
     * @param string $help
     * @return ValidationRule
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
     * Set rangeMin
     *
     * @param integer $rangeMin
     * @return ValidationRule
     */
    public function setRangeMin($rangeMin)
    {
        $this->rangeMin = $rangeMin;
    
        return $this;
    }

    /**
     * Get rangeMin
     *
     * @return integer 
     */
    public function getRangeMin()
    {
        return $this->rangeMin;
    }

    /**
     * Set rangeMax
     *
     * @param integer $rangeMax
     * @return ValidationRule
     */
    public function setRangeMax($rangeMax)
    {
        $this->rangeMax = $rangeMax;
    
        return $this;
    }

    /**
     * Get rangeMax
     *
     * @return integer 
     */
    public function getRangeMax()
    {
        return $this->rangeMax;
    }

    /**
     * Set validation
     *
     * @param \Teclliure\QuestionBundle\Entity\Validation $validation
     * @return ValidationRule
     */
    public function setValidation(\Teclliure\QuestionBundle\Entity\Validation $validation = null)
    {
        $this->validation = $validation;
    
        return $this;
    }

    /**
     * Get validation
     *
     * @return \Teclliure\QuestionBundle\Entity\Validation 
     */
    public function getValidation()
    {
        return $this->validation;
    }

    public function isMinMaxValid(ExecutionContext $context)
    {
        // somehow you have an array of "fake names"
        $fakeNames = array();

        // check if the name is actually a fake name
        if ($this->getRangeMin() > $this->getRangeMax()) {
            $context->addViolationAtSubPath('rangeMin', 'Range min could not be lower than range max', array(), null);
        }
    }
}