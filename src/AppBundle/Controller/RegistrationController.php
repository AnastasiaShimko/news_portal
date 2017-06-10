<?php
namespace AppBundle\Controller;

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

    /**
     * @Route("/register", name="registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $form = $this->createRegistrationForm($request);
        if ($this->tryCreateUser($form, $em, $passwordEncoder)) {
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

    private function tryCreateUser(Form $form, EntityManagerInterface $em,  UserPasswordEncoderInterface $passwordEncoder){
        if ($form->isSubmitted() && $form->isValid() && !$this->userExists($em)) {
            $this->createUser($em, $passwordEncoder);
            return true;
        }
        return false;
    }

    private function userExists(EntityManagerInterface $em){
        $repository = $em->getRepository('AppBundle:User');
        $user= $repository->findOneBy(
            array('email' => $this->userInForm->getEmail())
        );
        return $user;
    }

    private function createUser(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder){
        $user = new User();
        $this->mergeUser($user, $passwordEncoder);
        $em->persist($user);
        $em->flush();
    }


    private function mergeUser(User $user, UserPasswordEncoderInterface $passwordEncoder){
        $password = $passwordEncoder->encodePassword($user, $this->userInForm->getPassword());
        $user->setPassword($password);
        $user->setEmail($this->userInForm->getEmail());
        $user->setNotification($this->userInForm->getNotification());
    }
}