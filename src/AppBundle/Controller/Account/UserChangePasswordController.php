<?php

namespace AppBundle\Controller\Account;

use AppBundle\Entity\User;
use AppBundle\Providers\UserProvider;
use AppBundle\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserChangePasswordController extends Controller
{

    /**
     * @Route("/password_change", name="password_change")
     */
    public function changeAction(EntityManagerInterface $em,  UserPasswordEncoderInterface $passwordEncoder,
                                 \Swift_Mailer $mailer, Request $request)
    {
        $email = $this->getEmailFromForm($request);
        if ($email && $this->changePasswordIfValidEmail($em, $email,  $passwordEncoder, $mailer)) {
            return $this->redirectToRoute('login');
        }
        return $this->render('user/email.html.twig');
    }

    private function changePasswordIfValidEmail(EntityManagerInterface $em, string $email,
                                           UserPasswordEncoderInterface  $passwordEncoder, \Swift_Mailer $mailer){
        $user = (new UserProvider())->getUser($em, $email);
        if (User::isValidUser($user)){
            $this->generateChangePassword($em, $user, $passwordEncoder, $mailer);
            return true;
        }
        return false;
    }

    private function generateChangePassword(EntityManagerInterface $em, User $user,
                                    UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer){
        $password = base64_encode(random_bytes(10));
        (new UserProvider())->changePassword($em, $user, $password, $passwordEncoder);
        $twig = $this->get('twig');
        (new Mailer())->sendChangePasswordMail($mailer, $user->getEmail(), $password, $twig);
    }

    public function getEmailFromForm(Request $request) {
        if ( $request->getMethod() == Request::METHOD_POST ) {
            return $request->get('email');
        }
        return null;
    }
}