<?php

namespace AppBundle\Entity;


class BasicUser
{
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
}