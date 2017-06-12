<?php
namespace AppBundle\Entity;

use AppBundle\Constants\RoleConstants;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User implements UserInterface, \Serializable
{

    public function __construct()
    {
        $this->role = 0;
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notification;

    /**
     * @ORM\Column(type="integer")
     */
    private $role;


    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array($this->getRole());
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->notification,
            $this->role,
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->notification,
            $this->role,
            // $this->salt
            ) = unserialize($serialized);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRole()
    {
        return RoleConstants::$ROLES_FROM_NUMBER[$this->role];
    }

    public function setRole(string $role)
    {
        $this->role = RoleConstants::$NUMBER_FROM_ROLES[$role];
    }

    public function setNotification(bool $notification)
    {
        $this->notification = $notification;

        return $this;
    }

    public function isNotification()
    {
        return $this->notification;
    }

    public static function isValidUser(User $user){
        return $user && $user->getRole()!= 'ROLE_NOT_CONFIRMED'
            && $user->getRole()!='ROLE_DELETED';
    }
}
