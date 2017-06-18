<?php

namespace AppBundle\Constants;

class RoleConstants
{
    public static $ROLES_FROM_NUMBER = array(0=>'ROLE_NOT_CONFIRMED', 1=>'ROLE_USER', 2=>'ROLE_MANAGER', 3=>'ROLE_ADMIN', 4=>'ROLE_DELETED');
    public static $NUMBER_FROM_ROLES = array('ROLE_NOT_CONFIRMED'=>0, 'ROLE_USER'=>1, 'ROLE_MANAGER'=>2, 'ROLE_ADMIN'=>3, 'ROLE_DELETED'=>4);

}