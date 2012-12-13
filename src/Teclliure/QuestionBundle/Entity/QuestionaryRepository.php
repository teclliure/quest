<?php

namespace Teclliure\QuestionBundle\Entity;

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use \Teclliure\CategoryBundle\Entity\Subcategory;
use Doctrine\Common\Collections\ArrayCollection;

class QuestionaryRepository extends SortableRepository
{
    public function queryAll() {
        $em = $this->getEntityManager();

        $dql = 'SELECT q FROM TeclliureQuestionBundle:Questionary q';
        $query = $em->createQuery($dql);
        return $query;
    }

    public function findQuestions($questionary) {
        $em = $this->getEntityManager();

        $dql = 'SELECT q FROM TeclliureQuestionBundle:Question q WHERE q.questionary = :questionary ORDER BY q.category desc, q.position asc';

        $query = $em->createQuery($dql);
        $query->setParameter('questionary', $questionary->getId());

        return $query->getResult();
    }

    public function findValidations($questionary) {
        $em = $this->getEntityManager();

        $dql = 'SELECT v FROM TeclliureQuestionBundle:Validation v WHERE v.questionary = :questionary ORDER BY v.category desc, v.position asc';

        $query = $em->createQuery($dql);
        $query->setParameter('questionary', $questionary->getId());

        return $query->getResult();
    }

    public function deleteSubcategories($questionary) {
        $em = $this->getEntityManager();

        $dql = 'DELETE FROM TeclliureQuestionBundle:QuestionarySubcategory q WHERE q.questionary = :questionary';

        $query = $em->createQuery($dql);
        $query->setParameter('questionary', $questionary->getId());
        return $query->execute();
    }

    public function addSubcategories($questionary, $subcategories) {
        $em = $this->getEntityManager();

        $subcategoryRepository = $em->getRepository('TeclliureCategoryBundle:Subcategory');

        foreach ($subcategories as $subcat) {
            if (is_array($subcat)) {
                foreach ($subcat as $subcat2) {
                    $subcat = $subcategoryRepository->find($subcat2);

                    $questionarySubcat = new QuestionarySubcategory();
                    $questionarySubcat->setQuestionary($questionary);
                    $questionarySubcat->setSubcategory ($subcat);

                    $em->persist($questionarySubcat);
                }
            }
            else {
                if ($subcat) {
                    $subcat = $subcategoryRepository->find($subcat);

                    $questionarySubcat = new QuestionarySubcategory();
                    $questionarySubcat->setQuestionary($questionary);
                    $questionarySubcat->setSubcategory ($subcat);

                    $em->persist($questionarySubcat);
                }
            }
        }
    }

    public function getSubcategories($questionary, $category) {
        $em = $this->getEntityManager();

        $dql = 'select s FROM TeclliureCategoryBundle:Subcategory s JOIN s.questionarySubcategory qs WHERE qs.questionary = :questionary AND s.category = :category';

        $query = $em->createQuery($dql);

        $query->setParameter('questionary', $questionary->getId());
        $query->setParameter('category', $category->getId());

        $result = new ArrayCollection($query->getResult());

        return $result;
    }
}