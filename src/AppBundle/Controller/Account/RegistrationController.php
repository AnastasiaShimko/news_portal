<?php
namespace AppBundle\Controller\Account;

use AppBundle\Entity\RegisteredUser;
use AppBundle\Entity\User;
use AppBundle\Form\RegistrationForm;
use AppBundle\Provider\UserProvider;
use AppBundle\Service\UsersMailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    private $userInForm;

    /**
     * @Route("/register", name="registration")
     */
    public function registerAction(Request $request)
    {
        $form = $this->createRegistrationForm($request);
        if ($this->tryCreateUser($form)) {
            return $this->redirectToRoute('login');
        }
        return $this->render(
            'user/register.html.twig',
            array('form' => $form->createView())
        );
    }

    private function createRegistrationForm(Request $request){
        if (!$this->userInForm){
            $this->userInForm = new RegisteredUser();
        }
        $form = $this->createForm(RegistrationForm::class, $this->userInForm);
        $form->handleRequest($request);
        return $form;
    }

    private function tryCreateUser(Form $form){
        $userProvider = $this->container->get(UserProvider::class);
        if ($form->isSubmitted() && $form->isValid()
            && !$userProvider->getUser($this->userInForm->getEmail())) {
            $user = $userProvider->createUser($this->userInForm);
            $this->sendRegistrationMail($user);
            return true;
        }
        return false;
    }


    private function sendRegistrationMail(User $user){
        $mailer = $this->container->get(UsersMailer::class);
        $info =  array('id' => md5($user->getId().$user->getPassword().$user->getEmail()));
        $mailer->sendMessage('Confirm Registration', 'fea.ortenore@gmail.com',
            'user/registration.html.twig', $info);#$user->getEmail()
    }

}