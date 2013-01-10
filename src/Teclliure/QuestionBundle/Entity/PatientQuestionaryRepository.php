<?php

namespace Teclliure\QuestionBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer;
use Doctrine\Common\Collections\ArrayCollection;

class PatientQuestionaryRepository extends EntityRepository
{
    public function findQuestionsWithAnswers($patientQuestionary) {
        $em = $this->getEntityManager();

        $patientQuestionaryAnswers = $patientQuestionary->getPatientQuestionaryAnswers();
        $dql = 'SELECT q FROM TeclliureQuestionBundle:Question q WHERE q.questionary = :questionary ORDER BY q.category desc, q.position asc';

        $query = $em->createQuery($dql);
        $query->setParameter('questionary', $patientQuestionary->getQuestionary()->getId());

        $questions = $query->getResult();
        foreach ($questions as $question) {
            foreach ($patientQuestionaryAnswers as $key=>$patientQuestionaryAnswer) {
                if ($patientQuestionaryAnswer->getQuestion()->getId() == $question->getId()) {
                    $question->setPatientQuestionAnswer($patientQuestionaryAnswer);
                    unset ($patientQuestionaryAnswers[$key]);
                }
            }
        }

        return $questions;
    }

    public function deleteAnswers($patientQuestionary) {
        $em = $this->getEntityManager();

        $dql = 'DELETE FROM TeclliureQuestionBundle:PatientQuestionaryAnswer pqs WHERE pqs.patientQuestionary = :patientQuestionary';

        $query = $em->createQuery($dql);
        $query->setParameter('patientQuestionary', $patientQuestionary->getId());
        return $query->execute();
    }

    public function addAnswers($patientQuestionary, $answers) {
        $em = $this->getEntityManager();

        $answersRepository = $em->getRepository('TeclliureQuestionBundle:Answer');
        $questionsRepository = $em->getRepository('TeclliureQuestionBundle:Question');

        foreach ($answers as $questionId => $answer) {
            if ($answer && $questionId) {
                $answer = $answersRepository->find($answer);
                $question = $questionsRepository->find($questionId);

                if ($answer && $question) {
                    $patientQuestionaryAnswer = new PatientQuestionaryAnswer($patientQuestionary);
                    $patientQuestionaryAnswer->setPatientQuestionary($patientQuestionary);
                    $patientQuestionaryAnswer->setQuestion($question);
                    $patientQuestionaryAnswer->setAnswer($answer);

                    $em->persist($patientQuestionaryAnswer);
                }
            }
        }
    }

    public function findValidations($questionary) {
        $em = $this->getEntityManager();

        // $patientQuestionaryAnswers = $patientQuestionary->getPatientQuestionaryAnswers();
        $dql = 'SELECT v FROM TeclliureQuestionBundle:Validation v WHERE v.questionary = :questionary ORDER BY v.name desc';

        $query = $em->createQuery($dql);
        $query->setParameter('questionary', $questionary->getId());

        /*$questions = $query->getResult();
        foreach ($questions as $question) {
            foreach ($patientQuestionaryAnswers as $key=>$patientQuestionaryAnswer) {
                if ($patientQuestionaryAnswer->getQuestion()->getId() == $question->getId()) {
                    $question->setPatientQuestionAnswer($patientQuestionaryAnswer);
                    unset ($patientQuestionaryAnswers[$key]);
                }
            }
        }*/

        return $query->getResult();
    }
}