<?php

namespace Teclliure\PatientBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PatientAdmin extends Admin {

    public function getFormTheme()
    {
        return array(':Sonata:form_theme.html.twig');
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
        ->addIdentifier('name', null, array('label' => 'Name'))
        ->add('identification')
        ->add('email')
        ->add('user')
        ->add('active', null, array('label' => 'Active ?'))
        ->add('created')
        ->add('updated')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
        ->add('name')
        ->add('identification')
        ->add('email')
        ->add('phone')
        ->add('user')
        ->add('active')
        ;
    }

    protected function configureFormFields(FormMapper $mapper)
    {
        $mapper
        ->add('name')
        ->add('identification')
        ->add('email')
        ->add('phone')
        ->add('address')
        ->add('user')
        ->add('birthDate','date')
        ->add('notes')
        ->add('active','checkbox', array('required' => false))
        ;
    }
}