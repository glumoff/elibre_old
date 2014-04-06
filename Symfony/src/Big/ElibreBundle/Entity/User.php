<?php

namespace Big\ElibreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of User
 *
 * @author Alexander Glumoff <glumoff at gmail.com>
 */

/**
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User implements UserInterface {

  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=25, unique=true)
   */
  private $username;

  /**
   * @ORM\Column(type="string", length=32)
   */
  private $salt;

  /**
   * @ORM\Column(type="string", length=64)
   */
  private $password;

  /**
   * @ORM\Column(type="string", length=60, unique=true)
   */
  private $email;

  /**
   * @ORM\Column(name="is_active", type="boolean")
   */
  private $isActive;

  public function __construct() {
    $this->isActive = true;
    $this->salt = md5(uniqid(null, true));
  }

  public function eraseCredentials() {
    
  }

  public function getPassword() {
    return $this->password;
  }

  public function getRoles() {
    return array('ROLE_USER');
  }

  public function getSalt() {
    return $this->salt;
  }

  public function getUsername() {
    return $this->username;
  }

}
