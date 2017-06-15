<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields="email", message="Name already taken")
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User extends BasicUser implements AdvancedUserInterface, \Serializable
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
    protected $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=64)
     */
    protected $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
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
    protected $role;

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
     * @param User $user
     * @return bool
     */
    public static function isValidUser(User $user){
        return $user && $user->getRole()!= 'ROLE_NOT_CONFIRMED'
            && $user->getRole()!='ROLE_DELETED';
    }

    /**
     * Checks whether the user's account has expired.
     * @return bool true if the user's account is non expired, false otherwise
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     * @return bool true if the user is not locked, false otherwise
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     * @return bool true if the user's credentials are non expired, false otherwise
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     * @return bool true if the user is enabled, false otherwise
     * @see DisabledException
     */
    public function isEnabled()
    {
        return self::isValidUser($this);
    }
}
