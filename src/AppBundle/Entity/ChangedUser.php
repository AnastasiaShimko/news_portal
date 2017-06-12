<?php


namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ChangedUser extends BasicUser
{
    /**
     * @Assert\NotBlank()
     */
    private $oldPassword;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param string $oldPassword
     */
    public function setOldPassword(string $oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }
}