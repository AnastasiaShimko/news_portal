<?php

namespace AppBundle\Controller\Account;


use AppBundle\Entity\User;
use AppBundle\Provider\UserProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UserValidationController extends Controller
{
    /**
     * @Route("/validate/{id}", name="validation")
     */
    public function validateAction($id)
    {
        $user = $this->getUserByConfirmId($id);
        if ($user) {
            $this->container->get(UserProvider::class)->changeRole($user, 'ROLE_USER');
            return $this->redirectToRoute('login');
        }
        return $this->redirectToRoute('registration');
    }


    private function getUserByConfirmId($id){
        $user = null;
        foreach ($this->container->get(UserProvider::class)->getAllUsersByRole('ROLE_NOT_CONFIRMED') as $value){
            if($this->checkConfirmId($value, $id)){
                $user = $value;
            }
        }
        return $user;
    }

    private function checkConfirmId(User $user, $id){
        return md5($user->getId().$user->getPassword().$user->getEmail())==$id;
    }

}