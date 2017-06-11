<?php
namespace AppBundle\Controller\Account;

use AppBundle\Entity\RegisteredUser;
use AppBundle\Form\RegistrationForm;
use AppBundle\Providers\UserProvider;
use AppBundle\Services\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationController extends Controller
{
    private $userInForm;

    /**
     * @Route("/register", name="registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder,
                                   EntityManagerInterface $em, \Swift_Mailer $mailer)
    {
        $form = $this->createRegistrationForm($request);
        if ($this->tryCreateUser($form, $em, $passwordEncoder, $mailer)) {
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

    private function tryCreateUser(Form $form, EntityManagerInterface $em,  UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer){
        $userProvider = new UserProvider();
        if ($form->isSubmitted() && $form->isValid()
            && !$userProvider->getUser($em, $this->userInForm->getEmail())) {
            $user = $userProvider->createUser($this->userInForm, $em, $passwordEncoder);
            $twig = $this->get('twig');
            (new Mailer())->sendRegistrationMail($mailer, $user, $twig);
            return true;
        }
        return false;
    }

}