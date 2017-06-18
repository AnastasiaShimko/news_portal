<?php

namespace AppBundle\Entity;

class Roles
{
    private $roles;

    public function _construct(){
        $this->roles = array();
    }

    public function getRoles():array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function addRole(int $id, string $role)
    {
        $this->roles[$id] = $role;
    }

    public function getRole(int $id):string
    {
        return $this->roles[$id];
    }
}