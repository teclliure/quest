<?php

namespace Teclliure\UserBundle\Model;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Teclliure\UserBundle\Entity\User;

class UserManager
{
    protected $encoderFactory;

     /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     *
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Returns an empty user instance
     *
     * @return User
     */
    public function createUser()
    {
        $user = new User();

        return $user;
    }

    /**
     * Sets user password
     *
     */
    public function setUserPassword(User $user, $password)
    {
        $salt = md5(time());
        $encoder = $this->encoderFactory->getEncoder($user);
        $passwordEncoded = $encoder->encodePassword($password, $salt);

        $user->setSalt($salt);
        $user->setPassword($passwordEncoded);
    }
}