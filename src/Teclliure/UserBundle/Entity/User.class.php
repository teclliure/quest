<?php

namespace Teclliure\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;

  /** @ORM\Column(type="string", length=100, unique=true) */
  protected $email;

  /** @ORM\Column(type="string", length=255) */
  protected $password;

  /** @ORM\Column(type="string", length=255) */
  protected $salt;

  public function __toString() {
    return $this->getEmail();
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function getEmail()
  {
    return $this->email;
  }


  public function getId()
  {
    return $this->id;
  }

}
