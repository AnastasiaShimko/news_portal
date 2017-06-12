<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ChangeUserController extends Controller
{
    /**
     * @Route("/change_user", name="change_user")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        return $this->render('main/main.html.twig', array(
        ));
    }
}