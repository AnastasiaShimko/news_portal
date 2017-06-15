<?php

namespace AppBundle\Entity;


use AppBundle\Constants\RoleConstants;

class BasicUser
{
    protected $id;

    protected $role;

    protected $password;

    protected $email;

    protected $notification;

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param bool $notification
     */
    public function setNotification(bool $notification)
    {
        $this->notification = $notification;
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
}