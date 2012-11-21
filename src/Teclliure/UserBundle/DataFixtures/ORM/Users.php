<?php

namespace Teclliure\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Teclliure\UserBundle\Entity\User;
use Teclliure\UserBundle\Model\UserManager;

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
        $today = new \DateTime();
        $nextWeek = new \DateTime();
        $nextWeek->add(new \DateInterval('P7D'));
        $nextTwoWeek = new \DateTime();
        $nextTwoWeek->add(new \DateInterval('P14D'));
        $lastWeek = new \DateTime();
        $lastWeek->sub(new \DateInterval('P7D'));

        $dataArray = array(
            array('email' => 'marc@teclliure.net', 'password' => '1234','is_admin'=>1, 'expire_date'=> $nextWeek),
            array('email' => 'marc@sindominio.net', 'password' => '4321','is_admin'=>0, 'expire_date'=> $nextTwoWeek),
            array('email' => 'marc@disabled.net', 'password' => '4321','disabled'=>1, 'is_admin'=>0, 'expire_date'=> $today),
            array('email' => 'marc@expired.net', 'password' => '4321', 'is_admin'=>0, 'expire_date'=> $lastWeek),
        );
        $encoderFactory = $this->container->get('security.encoder_factory');
        $userManager = new UserManager($encoderFactory);

        foreach ($dataArray as $key => $data) {
            $entity = $userManager->createUser();
            $entity->setPlainPassword($data['password']);
            $entity->setEmail($data['email']);
            $entity->setIsAdmin($data['is_admin']);
            if (isset($data['disabled']) && $data['disabled'])
            {
                $entity->setActive(false);
            }

            if (isset($data['expire_date']) && $data['expire_date'])
            {
                $entity->setExpireDate($data['expire_date']);
            }
            $userManager->updatePassword($entity);
            $manager->persist($entity);

            $this->addReference('user'.$key, $entity);
        }
        $manager->flush();
    }
}
