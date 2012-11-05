<?php

namespace Teclliure\QuestionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Teclliure\QuestionBundle\Entity\Questionary;

class Questionaries extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $dataArray = array(
            array('name' => 'Test 1', 'desc' => 'Questionary 1 desc'),
            array('name' => 'Test 2', 'desc' => 'Questionary 2 desc'),
        );
        foreach ($dataArray as $key => $data) {
            $entity = new Questionary();
            $entity->setName($data['name']);
            $entity->setDescription($data['desc']);
            $manager->persist($entity);

            $this->addReference('questionary'.$key, $entity);
        }
        $manager->flush();
    }
}
