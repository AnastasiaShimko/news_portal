<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ChangedUser extends BasicUser
{
    /**
     * @Assert\NotBlank()
     */
    private $oldPassword;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }
}