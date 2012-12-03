<?php

namespace Teclliure\CategoryBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;

class CategoryFieldsSubscriber implements EventSubscriberInterface
{
    private $factory;

    private $em;

    public function __construct(FormFactoryInterface $factory, EntityManager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // Add category selectors
        $repository = $this->em->getRepository('TeclliureCategoryBundle:Category');

        $categories = $repository->findActive();
        foreach ($categories as $category) {
            $options = array('mapped' => false, 'field'=>$category->getName());

            $form->add($this->factory->createNamed('cat'.$category->getId(), 'text', $options));
        }

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. You're only concerned with when
        // setData is called with an actual Entity object in it (whether new
        // or fetched with Doctrine). This if statement lets you skip right
        // over the null condition.
        /*if (null === $data) {
            return;
        }

        // check if the product object is "new"
        if (!$data->getId()) {
            $form->add($this->factory->createNamed('name', 'text'));
        }*/
    }
}

