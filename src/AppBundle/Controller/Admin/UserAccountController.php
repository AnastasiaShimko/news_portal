<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Entity\Roules;
use AppBundle\Form\UserChangeByAdminForm;
use AppBundle\Providers\UserProvider;
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

    private function createUserChangeForm(array $users, Request $request){
        $this->roles = new Roules();
        foreach ($users as $user){
            $this->roles->addRole($user->getId(), $user->getRole());
        }
        $form = $this->createForm(UserChangeByAdminForm::class, $this->roles);
        $form->handleRequest($request);
        return $form;
    }

    public function changeUsersRole(array $users, EntityManagerInterface $em){
        foreach ($users as $user){
            (new UserProvider())->changeRole($user,  $this->roles->getRole($user->getId()), $em);
        }
    }

    private function getAllActiveUsers(EntityManagerInterface $em){
        $userProvider = new UserProvider();

        return   array_merge(array_merge($userProvider->getAllUsersByRole($em, 'ROLE_USER'),
            $userProvider->getAllUsersByRole($em, 'ROLE_MANAGER')),
            $userProvider->getAllUsersByRole($em, 'ROLE_ADMIN'));
    }
}