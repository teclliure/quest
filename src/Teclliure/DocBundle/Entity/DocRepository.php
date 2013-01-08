<?php

namespace Teclliure\DocBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Teclliure\CategoryBundle\Entity\Subcategory;
use Teclliure\DocBundle\Entity\DocSubcategory;
use Doctrine\Common\Collections\ArrayCollection;

class DocRepository extends EntityRepository
{
    public function deleteSubcategories($doc) {
        $em = $this->getEntityManager();

        $dql = 'DELETE FROM TeclliureDocBundle:DocSubcategory d WHERE d.doc = :doc';

        $query = $em->createQuery($dql);
        $query->setParameter('doc', $doc->getId());
        return $query->execute();
    }

    public function addSubcategories($doc, $subcategories) {
        $em = $this->getEntityManager();

        $subcategoryRepository = $em->getRepository('TeclliureCategoryBundle:Subcategory');

        foreach ($subcategories as $subcat) {
            if (is_array($subcat)) {
                foreach ($subcat as $subcat2) {
                    $subcat = $subcategoryRepository->find($subcat2);

                    $docSubcat = new DocSubcategory();
                    $docSubcat->setDoc($doc);
                    $docSubcat->setSubcategory ($subcat);

                    $em->persist($docSubcat);
                }
            }
            else {
                if ($subcat) {
                    $subcat = $subcategoryRepository->find($subcat);

                    $docSubcat = new DocSubcategory();
                    $docSubcat->setDoc($doc);
                    $docSubcat->setSubcategory ($subcat);

                    $em->persist($docSubcat);
                }
            }
        }
    }

    public function getSubcategories($doc, $category) {
        $em = $this->getEntityManager();

        $dql = 'select s FROM TeclliureCategoryBundle:Subcategory s JOIN s.docs ds WHERE ds.doc = :doc AND s.category = :category';

        $query = $em->createQuery($dql);

        $query->setParameter('doc', $doc->getId());
        $query->setParameter('category', $category->getId());

        $result = new ArrayCollection($query->getResult());

        return $result;
    }
}