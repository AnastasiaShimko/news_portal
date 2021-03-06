<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Entity\Roles;
use AppBundle\Form\UserChangeByAdminForm;
use AppBundle\Provider\UserProvider;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class UserAccountController extends Controller
{
    private $roles;

    /**
     * @Route("/accounts", name="account_control")
     */
    public function controlAction(Request $request, EntityManager $em)
    {
        $users= $this->getAllActiveUsers();
        $form = $this->createUserChangeForm($users, $request);
        if ($form->isValid()){
            $this->changeUsersRole($users);
            $users= $this->getAllActiveUsers();
            $form = $this->createUserChangeForm($users, $request);
        }return $this->render(
            'admin/show.html.twig',
            array('users' => $users, 'form' => $form->createView(),
                'category_root'=>$em->getRepository(Category::class)->getCategoryRoot())
        );
    }

    private function createUserChangeForm(array $users, Request $request):Form
    {
        $this->roles = new Roles();
        foreach ($users as $user){
            $this->roles->addRole($user->getId(), $user->getRole());
        }
        $form = $this->createForm(UserChangeByAdminForm::class, $this->roles);
        $form->handleRequest($request);
        return $form;
    }

    public function changeUsersRole(array $users)
    {
        foreach ($users as $user){
            $this->container->get(UserProvider::class)->changeRole($user,  $this->roles->getRole($user->getId()));
        }
    }

    private function getAllActiveUsers():array
    {
        $userProvider = $this->container->get(UserProvider::class);
        return   array_merge(array_merge($userProvider->getAllUsersByRole('ROLE_USER'),
            $userProvider->getAllUsersByRole('ROLE_MANAGER')),
            $userProvider->getAllUsersByRole('ROLE_ADMIN'));
    }
}