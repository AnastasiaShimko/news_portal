<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Entity\Roules;
use AppBundle\Form\UserChangeByAdminForm;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserAccountController extends Controller
{

    private $roles;
    /**
     * @Route("/accounts", name="account_control")
     */
    public function controlAction(EntityManagerInterface $em, Request $request){
        $users= $this->getAllActiveUsers($em);
        $form = $this->createUserChangeForm($users, $request);
        if ($form->isValid()){
            $this->changeUsersRole($users, $em);
        }
        return $this->render(
            'admin/show.html.twig',
            array('users' => $users, 'form' => $form->createView())
        );
    }


    private function changeUsersRole(array $users, EntityManagerInterface $em){
        foreach ($users as $user){
            $this->changeUserRole($user, $this->roles);
        }
        $em->flush();
    }

    private function changeUserRole(User $user){
        if($this->roles->getRole($user->getId())
            && $user->getRole()!=$this->roles->getRole($user->getId())){
            $user->setRole($this->roles->getRole($user->getId()));
        }
    }

    private function createUserChangeForm(array $users, Request $request){
        $this->roles = new Roules();
        foreach ($users as $user){
            $this->roles->addRole($user->getId(), $user->getRole());
        }
        $form = $this->createForm(UserChangeByAdminForm::class, $this->roles);
        $form->handleRequest($request);
        return $form;
    }

    private function getAllActiveUsers(EntityManagerInterface $em){
        $repository = $em->getRepository('AppBundle:User');
        return $repository->findBy(array('role' => 3))+$repository->findBy(array('role' => 2))+$repository->findBy(array('role' => 1));
    }
}