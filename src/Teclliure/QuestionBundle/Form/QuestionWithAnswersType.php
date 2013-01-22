<?php

namespace Teclliure\QuestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Teclliure\QuestionBundle\Entity\Question;
use Teclliure\PatientBundle\Form\CustomType\ChoiceType;

class QuestionWithAnswersType extends AbstractType
{
    protected $question;
    protected $selectedValue;

    public function setQuestion(Question $question, $selectedValue = null) {
        $this->question = $question;
        $this->selectedValue = $selectedValue;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $answers = $this->question->getAnswers();
        $choices = array();
        foreach ($answers as $answer) {
            $choices[$answer->getId()] = '<span class="tooltiplink" title="'.$answer->getAnswer().'" data-content="'.$answer->getHelp().'">'.$answer->getAnswer().'</span>';
        }

        $attr = array('class'=>'patientResponseLabel');

        $builder->add('answer', new ChoiceType(), array(
        // $builder->add('answer', 'choice', array(
            'choices'   => $choices,
            'required'  => true,
            'expanded'  => true,
            'multiple'  => false,
            'label'     => false,
            'attr'      => $attr,
            'data'     => $this->selectedValue
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    public function getName()
    {
        return 'teclliure_questionbundle_questionwithanswerstype';
    }
}
