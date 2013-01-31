<?php

namespace Teclliure\QuestionBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Teclliure\QuestionBundle\Entity\Report;

class ReportRepository extends EntityRepository
{

    public function findWithResults($id) {
        $em = $this->getEntityManager();
        $result = $this->find($id);

        if ($result) {
            $patientQuestionaries = $result->getPatientQuestionaries();
            foreach ($patientQuestionaries as $key=>$patientQuestionary) {
                $selectedValidations = $patientQuestionary->getValidations();
                foreach ($selectedValidations as $key2=>$selectedValidation) {
                    $totalValue = $patientQuestionary->getTotalValue($selectedValidation);
                    $selectedValidation->totalValue = $totalValue;
                    $rules = $selectedValidation->getValidationRules();
                    foreach ($rules as $rule) {
                        if ($totalValue >= $rule->getRangeMin() && $totalValue <= $rule->getRangeMax()) {
                            $selectedValidation->setSelectedValidationRule($rule);
                            break;
                        }
                    }
                }
            }
        }
        return $result;
    }
}