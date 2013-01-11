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

    public function findPatientQuestionaries($patient) {
        $em = $this->getEntityManager();

        $dql = 'SELECT pq FROM TeclliureQuestionBundle:PatientQuestionary pq WHERE pq.patient = :patient ORDER BY pq.updated desc';

        $query = $em->createQuery($dql);
        $query->setParameter('patient', $patient->getId());

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

        $dql = 'select s FROM TeclliureCategoryBundle:Subcategory s JOIN s.questionaries qs WHERE qs.questionary = :questionary AND s.category = :category';

        $query = $em->createQuery($dql);

        $query->setParameter('questionary', $questionary->getId());
        $query->setParameter('category', $category->getId());

        $result = new ArrayCollection($query->getResult());

        return $result;
    }

    public function getQuestionariesByCategory($active = true) {
        $em = $this->getEntityManager();

        // $dql = 'select q, qs, s, c FROM TeclliureQuestionBundle:Questionary q LEFT JOIN q.subcategories qs JOIN qs.subcategory s JOIN s.category c ORDER BY q.name ASC';
        if ($active) {
            $dql = 'select q FROM TeclliureQuestionBundle:Questionary q LEFT JOIN q.subcategories qs LEFT JOIN qs.subcategory s LEFT JOIN s.category c where q.active = :active ORDER BY c.name ASC, q.name ASC';
        }
        else {
            $dql = 'select q FROM TeclliureQuestionBundle:Questionary q LEFT JOIN q.subcategories qs LEFT JOIN qs.subcategory s LEFT JOIN s.category c ORDER BY c.name ASC, q.name ASC';
        }

        $query = $em->createQuery($dql);

        if ($active) {
            $query->setParameter('active', true);
        }

        $resultArray = array();
        $result = $query->execute();
        foreach ($result as $questionary) {
            $subcats = $questionary->getSubcategories();
            if (count($subcats)) {
                foreach ($subcats as $subcat) {
                    $subcat = $subcat->getSubcategory();
                    if ($subcat) {
                        if (!isset($resultArray ['categories'][$subcat->getCategory()->getId()]['cat'])) {
                            $resultArray ['categories'][$subcat->getCategory()->getId()]['cat'] = $subcat->getCategory();
                        }
                        if (!isset($resultArray ['categories'][$subcat->getCategory()->getId()]['subcats'][$subcat->getId()]['subcat'])) {
                            $resultArray ['categories'][$subcat->getCategory()->getId()]['subcats'][$subcat->getId()]['subcat'] = $subcat;
                        }
                        $resultArray ['categories'][$subcat->getCategory()->getId()]['subcats'][$subcat->getId()]['questionaries'][] = $questionary;
                    }
                    else {
                        print 'Questionary without subcats'.$questionary->getId();
                        $resultArray ['questionaries'][] = $questionary;
                    }
                }
            }
            else {
                $resultArray ['questionaries'][] = $questionary;
            }
        }
        // print_r ($resultArray);

        return $resultArray;
    }

    public function getDocs($questionary) {
        $em = $this->getEntityManager();

        $dql = 'select d FROM TeclliureDocBundle:Doc d JOIN d.subcategories s WHERE s.subcategory IN (select qs.id FROM TeclliureCategoryBundle:Subcategory qs JOIN qs.questionaries qss WHERE qss.questionary = :questionary)';
        // $dql = 'select s FROM TeclliureCategoryBundle:Subcategory s JOIN s.questionaries qs WHERE qs.questionary = :questionary AND s.category = :category';

        $query = $em->createQuery($dql);
        $query->setParameter('questionary', $questionary->getId());


        return $query->getResult();
    }
}