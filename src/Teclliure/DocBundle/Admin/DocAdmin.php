<?php

namespace Teclliure\DocBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class DocAdmin extends Admin {

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
        $mapper
        ->add('file', 'file', array('required' => false))
        ->add('description')
        ->add('active','checkbox', array('required' => false))
        ;
    }

    public function preUpdate($doc) {
        // the file property can be empty if the field is not required
        if (null !== $doc->file) {
            $doc->setName(sha1(uniqid(mt_rand(), true)).'.'.$doc->file->guessExtension());
        }
    }
}