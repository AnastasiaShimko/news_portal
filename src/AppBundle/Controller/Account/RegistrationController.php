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
    private $user;

    /**
     * @Route("/register", name="registration")
     */
    public function registerAction(Request $request)
    {
        $form = $this->createRegistrationForm($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->createUser();
            return $this->redirectToRoute('login');
        }
        return $this->render(
            'user/register.html.twig',
            array('form' => $form->createView())
        );
    }

    private function createRegistrationForm(Request $request){
        if (!$this->user){
            $this->user = new User();
        }
        $form = $this->createForm(RegistrationForm::class, $this->user);
        $form->handleRequest($request);
        return $form;
    }

    private function createUser(){
        $userProvider = $this->container->get(UserProvider::class);
        $userProvider->createUser($this->user);
        $this->sendRegistrationMail();
    }

    private function sendRegistrationMail(){
        $mailer = $this->container->get(UsersMailer::class);
        $info =  array(
            'id' => md5($this->user->getId().$this->user->getPassword().$this->user->getEmail())
        );
        $mailer->sendMessage('Confirm Registration', 'fea.ortenore@gmail.com',
            'user/registration.html.twig', $info);#$user->getEmail()
    }
}