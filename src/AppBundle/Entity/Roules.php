<?php

namespace AppBundle\Entity;


class Roules
{
    private $roles;

    public function _construct(){
        $this->roles = array();
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function addRole(int $id, string $role)
    {
        $this->roles[$id] = $role;
    }

    public function getRole(int $id)
    {
        return $this->roles[$id];
    }
}