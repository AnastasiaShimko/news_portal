<?php

namespace AppBundle\Controller\Account;


use AppBundle\Entity\User;
use AppBundle\Providers\UserProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UserValidationController extends Controller
{
    /**
     * @Route("/validate/{id}", name="validation")
     */
    public function validateAction($id, EntityManagerInterface $em)
    {
        $user = $this->getUserByConfirmId($id, $em);
        if ($user) {
            (new UserProvider())->changeRole($user, 'ROLE_USER', $em);
            return $this->redirectToRoute('login');
        }
        return $this->redirectToRoute('registration');
    }


    private function getUserByConfirmId($id, EntityManager $em){
        $user = null;
        foreach ((new UserProvider())->getAllUsersByRole($em, 'ROLE_NOT_CONFIRMED') as $value){
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