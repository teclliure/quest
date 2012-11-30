<?php

namespace Teclliure\CategoryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class SubcategoryAdmin extends Admin {

    public function getFormTheme()
    {
        return array(':Sonata:form_theme.html.twig');
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
        ->addIdentifier('name', null, array('label' => 'Name'))
        ->add('description')
        ->add('category')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
        ->add('name')
        ->add('description')
        ->add('category')
        ;
    }

    protected function configureFormFields(FormMapper $mapper)
    {
        $mapper
        ->add('name')
        ->add('description')
        ->add('category')
        ;
    }
}