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
        ->add('email','email')
        ->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'Two passwords must be the same',
            'options' => array('label' => 'Password 2')
        ))
        ->add('is_admin','checkbox', array('required' => false))
        ->add('active','checkbox', array('required' => false))
        ;
    }
}
