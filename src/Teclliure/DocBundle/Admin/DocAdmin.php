<?php

namespace Teclliure\DocBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Teclliure\CategoryBundle\Form\EventListener\CategoryFieldsSubscriber;

class DocAdmin extends Admin {
    /**
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     */
    public function __construct($code, $class, $baseControllerName, \Doctrine\ORM\EntityManager $em)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->em = $em;
    }

    public function getFormTheme()
    {
        return array(':Sonata:form_theme.html.twig');
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
        ->addIdentifier('name', null, array('label' => 'Name'))
        ->add('active', null, array('label' => 'Active ?'))
        ->add('file', 'string', array('template' => 'TeclliureDocBundle:Admin:list_custom.html.twig', 'label' => 'Link to file'))
        ->add('created')
        ->add('updated')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
        ->add('name')
        ->add('active')
        ;
    }

    protected function configureFormFields(FormMapper $mapper)
    {
        $modelRepository = $this->em->getRepository('TeclliureDocBundle:Doc');

        $mapper
        ->add('file', 'file', array('required' => false))
        ->add('description')
        ->add('active','checkbox', array('required' => false))
        ;

        $builder = $mapper->getFormBuilder();
        $subscriber = new CategoryFieldsSubscriber($builder->getFormFactory(), $this->em, $modelRepository);

        $builder->addEventSubscriber($subscriber);
    }

    public function preUpdate($doc) {
        // the file property can be empty if the field is not required
        if (null !== $doc->getFile()) {
            $doc->setName(sha1(uniqid(mt_rand(), true)).'.'.$doc->getFile()->guessExtension());
        }
    }

    public function postUpdate($doc) {
        $this->saveCategories($doc);
    }

    public function postPersist($doc) {
        $this->saveCategories($doc);
    }

    public function preRemove($doc) {
        $doc->doDeleteSubcategories($this->em);
        $this->em->flush();
    }

    public function saveCategories ($doc) {
        $doc->doSaveSubcategories($this->em);
        $this->em->flush();
    }
}