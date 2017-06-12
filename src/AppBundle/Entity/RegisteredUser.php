<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class RegisteredUser
{
    /**
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @Assert\NotBlank()
     */
    private $oldPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    private $notification;

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

    public function getOldPassword()
    {
        return $this->newPassword;
    }

    public function setOldPassword(string $oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }
}