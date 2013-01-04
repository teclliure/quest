<?php

namespace Teclliure\QuestionBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Teclliure\QuestionBundle\Entity\PatientQuestionaryAnswer;
use Doctrine\Common\Collections\ArrayCollection;

class PatientQuestionaryRepository extends EntityRepository
{
    public function findAnswersQuestions($patientQuestionary) {
        $em = $this->getEntityManager();

        $dql = 'SELECT q FROM TeclliureQuestionBundle:Question q WHERE q.questionary = :questionary ORDER BY q.category desc, q.position asc';

        $query = $em->createQuery($dql);
        $query->setParameter('questionary', $questionary->getId());

        return $query->getResult();
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

        foreach ($answers as $answer) {
            $answer = $answersRepository->find($answer);

            if ($answer) {
                $patientQuestionaryAnswer = new PatientQuestionaryAnswer();
                $patientQuestionaryAnswer->setPatientQuestionary($patientQuestionary);
                $patientQuestionaryAnswer->setAnswer($answer);

                $em->persist($patientQuestionaryAnswer);
            }
        }
    }
}