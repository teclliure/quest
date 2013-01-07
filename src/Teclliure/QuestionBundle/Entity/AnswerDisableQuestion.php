<?php
namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="answer_disable_question")
 * @ORM\Entity
 * 
 */
class AnswerDisableQuestion
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Answer", inversedBy="disables_questions")
     */
    private $answer;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Teclliure\QuestionBundle\Entity\Question", inversedBy="disabled_by_answers")
     */
    private $question;

    /**
     * Set answer
     *
     * @param \Teclliure\QuestionBundle\Entity\Answer $answer
     * @return AnswerDisableQuestion
     */
    public function setAnswer(\Teclliure\QuestionBundle\Entity\Answer $answer)
    {
        $this->answer = $answer;
    
        return $this;
    }

    /**
     * Get answer
     *
     * @return \Teclliure\QuestionBundle\Entity\Answer 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set question
     *
     * @param \Teclliure\QuestionBundle\Entity\Question $question
     * @return AnswerDisableQuestion
     */
    public function setQuestion(\Teclliure\QuestionBundle\Entity\Question $question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return \Teclliure\QuestionBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }
}