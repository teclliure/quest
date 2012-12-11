<?php

namespace Teclliure\CategoryBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

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
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_BIND => 'postBind',
        );
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. You're only concerned with when
        // setData is called with an actual Entity object in it (whether new
        // or fetched with Doctrine). This if statement lets you skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

        // $form->add($this->factory->createNamed('catW', 'text'));

        // Add category selectors
        $catRepository = $this->em->getRepository('TeclliureCategoryBundle:Category');
        $questionaryRepository = $this->em->getRepository('TeclliureQuestionBundle:Questionary');

        $categories = $catRepository->findActive();
        foreach ($categories as $category) {
            if (count($category->getSubcategories()))
            {
                // Create entity field options
                $options = array(
                    'mapped' => false,
                    'label' => $category->getName(),
                    'class' => 'TeclliureCategoryBundle:Subcategory',
                    'multiple' => $category->getIsMultiple(),
                    'required' => $category->getIsRequired(),
                    'query_builder' => function(EntityRepository $er) use ($category) {
                        $rootAlias = 's'.$category->getId();
                        return
                            $er->createQueryBuilder($rootAlias)
                            ->where($rootAlias.'.category = :category_id')
                            ->setParameter('category_id', $category->getId());
                    }
                );

                // Load current values from database
                $currentValues = null;
                if ($data->getId()) {
                    $currentValues = $questionaryRepository->getSubcategories($data, $category);

                    if (!$category->getIsMultiple()) {
                        $currentValues = $currentValues[0];
                    }
                }

                // Add field to form
                $form->add(
                      $this->factory->createNamed(
                          'cat'.$category->getId(),
                          'entity', $currentValues, $options
                      )
                );
            }
        }
    }

    public function postBind(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // $form->add($this->factory->createNamed('catW', 'text'));

        // Add category selectors
        $repository = $this->em->getRepository('TeclliureCategoryBundle:Category');

        $categories = $repository->findActive();
        $data->subcategories = array();
        foreach ($categories as $category) {
            if (count($category->getSubcategories()))
            {
                $data->subcategories[] = $form['cat'.$category->getId()]->getViewData();
            }
        }
    }
}

