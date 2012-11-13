<?php

namespace Teclliure\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class UserAdmin extends Admin {
    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
        ->addIdentifier('email', null, array('label' => 'Email'))
        ->add('active')
        ->add('is_admin')
        ->add('created')
        ->add('updated')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
        ->add('email')
        ->add('active')
        ->add('is_admin')
        ->add('created')
        ;
    }

    protected function configureFormFields(FormMapper $mapper)
    {
        $mapper
        ->add('email')
        ->add('password')
        ->add('is_admin','checkbox')
        ->add('active','checkbox')
        ;
    }
}
