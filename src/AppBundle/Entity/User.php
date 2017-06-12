<?php
namespace AppBundle\Entity;

use AppBundle\Constants\RoleConstants;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User extends BasicUser implements UserInterface, \Serializable
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
    protected $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $notification;

    /**
     * @ORM\Column(type="integer")
     */
    private $role;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return array($this->getRole());
    }

    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
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

    /**
     * @see \Serializable::unserialize()
     */
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return RoleConstants::$ROLES_FROM_NUMBER[$this->role];
    }

    /**
     * @param Role $role
     */
    public function setRole(string $role)
    {
        $this->role = RoleConstants::$NUMBER_FROM_ROLES[$role];
    }

    /**
     * @param User $user
     * @return bool
     */
    public static function isValidUser(User $user){
        return $user && $user->getRole()!= 'ROLE_NOT_CONFIRMED'
            && $user->getRole()!='ROLE_DELETED';
    }
}
