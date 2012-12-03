<?php

namespace Teclliure\PatientBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Teclliure\CategoryBundle\Entity\Category;
use Teclliure\CategoryBundle\Entity\Subcategory;

class Categories extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }


    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $entity = new Category();
            $entity->setName('Category '.$i);
            $entity->setDescription('Desc dsadf ds fdf safdsfs fsaffs fsdfsd');
            if ($i%2)
            {
                $entity->setIsRequired(true);
                $entity->setActive(true);
                $entity->setIsMultiple(true);
            }
            else
            {
                $entity->setIsRequired(false);
                $entity->setActive(false);
                $entity->setIsMultiple(false);
            }

            $manager->persist($entity);

            $this->addReference('cat'.$i, $entity);
        }

        for ($i = 0; $i < 5; $i++) {
            $entity = new Category();
            $entity->setName('Category sec '.$i);
            $entity->setDescription('Desc dsadf ds fdf safdsfs fsaffs fsdfsd');
            if ($i%2)
            {
                $entity->setActive(true);
                $entity->setIsRequired(false);
                $entity->setIsMultiple(false);
            }
            else
            {
                $entity->setActive(false);
                $entity->setIsRequired(true);
                $entity->setIsMultiple(false);
            }

            $manager->persist($entity);

            $this->addReference('cat_sec'.$i, $entity);
        }

        for ($i = 0; $i < 10; $i++) {
            $entity = new Subcategory();
            $entity->setName('Subcategory cat 0 '.$i);
            $entity->setDescription('Subcategory desc'.$i);
            $entity->setCategory($manager->merge($this->getReference('cat0')));

            $manager->persist($entity);
        }

        for ($i = 0; $i < 10; $i++) {
            $entity = new Subcategory();
            $entity->setName('Subcategory cat sec 0 '.$i);
            $entity->setDescription('Subcategory desc'.$i);
            $entity->setCategory($manager->merge($this->getReference('cat_sec0')));

            $manager->persist($entity);
        }

        for ($i = 0; $i < 10; $i++) {
            $entity = new Subcategory();
            $entity->setName('Subcategory cat 1 '.$i);
            $entity->setDescription('Subcategory desc'.$i);
            $entity->setCategory($manager->merge($this->getReference('cat1')));

            $manager->persist($entity);
        }

        for ($i = 0; $i < 10; $i++) {
            $entity = new Subcategory();
            $entity->setName('Subcategory cat sec 1'.$i);
            $entity->setDescription('Subcategory desc'.$i);
            $entity->setCategory($manager->merge($this->getReference('cat_sec1')));

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
