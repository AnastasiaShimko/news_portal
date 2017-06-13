<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\ChangedUser;
use AppBundle\Form\ChangeUserForm;
use AppBundle\Provider\UserProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ChangeUserController extends Controller
{
    private $userInForm;

    /**
     * @Route("/change_user", name="change_user")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        $form = $this->createChangeForm($request);

        if($this->tryChangeUser($form)){
            return $this->redirectToRoute('main');
        }
        return $this->render('main/change_user.html.twig', array(
            'form' => $form->createView()
            )
        );
    }

    private function tryChangeUser(Form $form)
    {
        $userProvider = $this->container->get(UserProvider::class);

        return $form->isSubmitted() && $form->isValid() &&
            $userProvider->changeUser($this->userInForm, $this->getUser());
    }

    private function createChangeForm(Request $request){
        $this->userInForm = new ChangedUser();
        $this->userInForm->setNotification($this->getUser()->getNotification());
        $form = $this->createForm(ChangeUserForm::class, $this->userInForm);
        $form->handleRequest($request);
        return $form;
    }
}