<?php

namespace Teclliure\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Teclliure\UserBundle\Entity\User;

class Users extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 0;
    }

    public function load(ObjectManager $manager)
    {
        $dataArray = array(
            array('email' => 'marc@teclliure.net', 'password' => '1234'),
            array('email' => 'marc@sindominio.net', 'password' => '4321'),
        );
        foreach ($dataArray as $key => $data) {
            $entity = new User();

            $salt = md5(time());
            $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($entity);
            $password = $encoder->encodePassword($data['password'], $salt);

            $entity->setEmail($data['email']);
            $entity->setPassword($password);
            $entity->setSalt($salt);

            $manager->persist($entity);

            $this->addReference('user'.$key, $entity);
        }
        $manager->flush();
    }
}
