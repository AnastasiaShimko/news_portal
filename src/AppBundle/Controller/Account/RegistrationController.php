<?php
namespace AppBundle\Controller\Account;

use AppBundle\Entity\RegisteredUser;
use AppBundle\Form\RegistrationForm;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationController extends Controller
{
    private $userInForm;
    private $user;


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

    private function sendMail(\Swift_Mailer $mailer, User $user){
        $message = new \Swift_Message('Confirm Email');
        $message->setFrom('shimkoanastasia@gmail.com');
        $message->setTo('fea.ortenore@gmail.com'); #$user->getEmail()
        $message->setBody(
                $this->renderView(
                'user/registration.html.twig',
                    array('id' => md5($user->getId().$user->getPassword().$user->getEmail()))
                ),
                'text/html'
            );
        $mailer->send($message);
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
        if ($form->isSubmitted() && $form->isValid() && !$this->userExists($em)) {
            $user = $this->createUser($em, $passwordEncoder);
            $this->sendMail($mailer, $user);
            return true;
        }
        return false;
    }

    private function userExists(EntityManagerInterface $em){
        $repository = $em->getRepository('AppBundle:User');
        $user= $repository->findOneBy(
            array('email' => $this->userInForm->getEmail())
        );
        return isset($user);
    }

    private function createUser(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder){
        $user = new User();
        $this->mergeUser($user, $passwordEncoder);
        $em->persist($user);
        $em->flush();
        $this->user = $user;
        return $user;
    }


    private function mergeUser(User $user, UserPasswordEncoderInterface $passwordEncoder){
        $password = $passwordEncoder->encodePassword($user, $this->userInForm->getPassword());
        $user->setPassword($password);
        $user->setEmail($this->userInForm->getEmail());
        $user->setNotification($this->userInForm->getNotification());
    }
}