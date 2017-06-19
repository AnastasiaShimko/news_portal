<?php

namespace AppBundle\Controller\Account;

use AppBundle\Entity\User;
use AppBundle\Provider\UserProvider;
use AppBundle\Service\UsersMailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class UserChangePasswordController extends Controller
{
    /**
     * @Route("/password_change", name="password_change")
     */
    public function changeAction(Request $request)
    {
        $email = $this->getEmailFromForm($request);
        if ($email && $this->changePasswordIfValidEmail($email)) {
            return $this->render(
                'user/confirm.html.twig',
                array('label' => 'You successfully changed your password. New password was sent to your email address by mail.')
            );
        }
        return $this->render('user/email.html.twig');
    }

    private function changePasswordIfValidEmail(string $email):bool
    {
        $user = $this->container->get(UserProvider::class)->getUser($email);
        if (User::isValidUser($user)){
            $this->generateChangePassword($user);
            return true;
        }
        return false;
    }

    private function generateChangePassword(User $user)
    {
        $password = base64_encode(random_bytes(10));
        $this->container->get(UserProvider::class)->codePassword($user, $password);
        $this->container->get(UserProvider::class)->changeUser();
        $this->sendChangePasswordMail($user->getEmail(), $password);
    }

    private function sendChangePasswordMail(string $email, string $newPassword)
    {
        $mailer = $this->container->get(UsersMailer::class);
        $info = array('password' => $newPassword);
        $mailer->sendMessage('Password Change Email', $email,
            'user/change.html.twig', $info);
    }

    private function getEmailFromForm(Request $request)
    {
        if ( $email = $request->get('email') ) {
            return $email;
        }
        return null;
    }
}