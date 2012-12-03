<?php

namespace Teclliure\PatientBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Teclliure\PatientBundle\Entity\Patient;

class Patients extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }


    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {
            $entity = new Patient();
            $entity->setName('Patient active '.$i);
            $entity->setIdentification('Ident__'.$i);
            $entity->setEmail('patient'.$i.'@test.net');
            $entity->setPhone('43432432'.$i);
            $entity->setActive(true);
            $entity->setUser($manager->merge($this->getReference('user0')));

            $manager->persist($entity);

            $this->addReference('patient'.$i, $entity);
        }

        for ($i = 0; $i < 50; $i++) {
            $entity = new Patient();
            $entity->setName('Patient inactive '.$i);
            $entity->setIdentification('Ident__In__'.$i);
            $entity->setEmail('patient'.$i.'@test.net');
            $entity->setPhone('43432432'.$i);
            $entity->setActive(true);
            $entity->setUser($manager->merge($this->getReference('user0')));

            $manager->persist($entity);

            $this->addReference('patientIn'.$i, $entity);
        }
        $manager->flush();
    }
}
