<?php

namespace Teclliure\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Teclliure\UserBundle\Model\UserManager;

class UserAdmin extends Admin {

    protected $encoderFactory;

    /**
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     */
    public function __construct($code, $class, $baseControllerName, EncoderFactoryInterface $encoder)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->encoderFactory = $encoder;
    }
    /*public function __construct(EncoderFactoryInterface $encoder)
    {
        $this->encoderFactory = $encoder;
    }*/

    public function getFormTheme()
    {
        return array(':Sonata:form_theme.html.twig');
    }

    public function prePersist($user)
    {
        $userManager = new UserManager($this->encoderFactory);
        $userManager->updatePassword($user);
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
        ->addIdentifier('email', null, array('label' => 'Email'))
        ->add('active')
        ->add('is_admin', null, array('label' => 'Admin ?'))
        ->add('created')
        ->add('updated')
        ->add('expire_date','date',array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'attr' => array('class' => 'date')
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
        ->add('email')
        ->add('active')
        ->add('is_admin')
        ->add('created')
        ->add('expire_date')
        ;
    }

    protected function configureFormFields(FormMapper $mapper)
    {
        $mapper
        ->add('email','email')
        ->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'required' => false,
            'invalid_message' => 'Two passwords must be the same',
            'first_options' => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat password')
        ))
        ->add('is_admin','checkbox', array('required' => false))
        ->add('active','checkbox', array('required' => false))
        ->add('expire_date','date',array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'attr' => array('class' => 'date')
        ))
        ;
    }
}
