<?php

namespace Teclliure\CategoryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class CategoryAdmin extends Admin {

    public function getFormTheme()
    {
        return array(':Sonata:form_theme.html.twig');
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
        ->addIdentifier('name', null, array('label' => 'Name'))
        ->add('active')
        ->add('is_required', null, array('label' => 'Required ?'))
        ->add('is_multiple', null, array('label' => 'Multiple select ?'))
        ->add('created')
        ->add('updated')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
        ->add('name')
        ->add('active')
        ->add('is_required')
        ->add('is_multiple')
        ;
    }

    protected function configureFormFields(FormMapper $mapper)
    {
        $mapper
        ->add('name')
        ->add('desc')
        ->add('active','checkbox', array('required' => false))
        ->add('is_required','checkbox', array('required' => false))
        ->add('is_multiple','checkbox', array('required' => false))
        ;
    }
}