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

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function setNotification(bool $notification)
    {
        $this->notification = $notification;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRole()
    {
        return RoleConstants::$ROLES_FROM_NUMBER[$this->role];
    }

    public function setRole(string $role)
    {
        $this->role = RoleConstants::$NUMBER_FROM_ROLES[$role];
    }
}