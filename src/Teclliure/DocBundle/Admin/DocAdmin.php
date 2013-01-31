<?php

namespace Teclliure\DocBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Teclliure\CategoryBundle\Form\EventListener\CategoryFieldsSubscriber;
use Doctrine\ORM\EntityRepository;

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
        ->add('questionaries', 'entity', array(
            'class'    => 'TeclliureQuestionBundle:Questionary' ,
            'property' => 'name',
            'expanded' => true ,
            'multiple' => true,
            'attr'     => array('class'=>'formCssCheckbox'),
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('q')
                    ->orderBy('q.name','ASC');

            }))
        ;
    }

    public function preUpdate($doc) {
        // the file property can be empty if the field is not required
        if (null !== $doc->getFile()) {
            $doc->setName(sha1(uniqid(mt_rand(), true)).'.'.$doc->getFile()->guessExtension());
        }
    }
}