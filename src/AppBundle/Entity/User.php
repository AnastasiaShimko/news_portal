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
     * @ORM\OneToOne(targetEntity="UserParameters")
     * @ORM\JoinColumn(name="parameters_id", referencedColumnName="id")
     */
    private $parameters;

    /**
     * @ORM\Column(type="integer")
     */
    protected $role;

    public function getUsername():string
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles():array
    {
        return array($this->getRole());
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->notification,
            $this->role,
            $this->parameters
            // $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->notification,
            $this->role,
            $this->parameters
            ) = unserialize($serialized);
    }

    public static function isValidUser(User $user):bool
    {
        return $user && $user->getRole()!= 'ROLE_NOT_CONFIRMED'
            && $user->getRole()!='ROLE_DELETED';
    }

    public function isAccountNonExpired():bool
    {
        return true;
    }

    public function isAccountNonLocked():bool
    {
        return true;
    }

    public function isCredentialsNonExpired():bool
    {
        return true;
    }

    public function isEnabled():bool
    {
        return self::isValidUser($this);
    }

    public function setParameters(UserParameters $parameters = null)
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function getParameters():UserParameters
    {
        return $this->parameters;
    }
}
