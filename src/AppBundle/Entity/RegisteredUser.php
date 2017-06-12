<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class RegisteredUser extends BasicUser
{
    /**
     * @Assert\NotBlank()
     */
    protected $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;
}