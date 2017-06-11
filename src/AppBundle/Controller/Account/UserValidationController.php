<?php

namespace AppBundle\Controller\Account;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UserValidationController extends Controller
{
    /**
     * @Route("/validate/{id}", name="validation")
     */
    public function validateAction($id, EntityManager $em)
    {
        $user = $this->getUserByConfirmId($id, $em);
        if ($user) {
            $this->changeRole($user, $em);
            return $this->redirectToRoute('login');
        }
        return $this->redirectToRoute('registration');
    }


    private function getUserByConfirmId($id, EntityManager $em){
        $user = null;
        foreach ($this->getAllNotValidUsers($em) as $value){
            if($this->checkConfirmId($value, $id)){
                $user = $value;
            }
        }
        return $user;
    }

    private function checkConfirmId(User $user, $id){
        return md5($user->getId().$user->getPassword().$user->getEmail())==$id;
    }

    private function getAllNotValidUsers(EntityManager $em){
        $repository = $em->getRepository('AppBundle:User');
        return $repository->findBy(array('role' => 0));
    }

    private function changeRole(User $user, EntityManager $em){
        $user->setRole('ROLE_USER');
        $em->flush();
    }
}