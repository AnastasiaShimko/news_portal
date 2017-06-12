<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Roules;
use AppBundle\Form\UserChangeByAdminForm;
use AppBundle\Provider\UserProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserAccountController extends Controller
{

    private $roles;

    /**
     * @Route("/accounts", name="account_control")
     */
    public function controlAction(Request $request){

        $users= $this->getAllActiveUsers();
        $form = $this->createUserChangeForm($users, $request);

        if ($form->isValid()){
            $this->changeUsersRole($users);
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

    public function changeUsersRole(array $users){
        foreach ($users as $user){
            $this->container->get(UserProvider::class)->changeRole($user,  $this->roles->getRole($user->getId()));
        }
    }

    private function getAllActiveUsers(){
        $userProvider = $this->container->get(UserProvider::class);

        return   array_merge(array_merge($userProvider->getAllUsersByRole('ROLE_USER'),
            $userProvider->getAllUsersByRole('ROLE_MANAGER')),
            $userProvider->getAllUsersByRole('ROLE_ADMIN'));
    }
}