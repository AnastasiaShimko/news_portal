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
            return $this->redirectToRoute('login');
        }
        return $this->render('user/email.html.twig');
    }

    private function changePasswordIfValidEmail(string $email){
        $user = $this->container->get(UserProvider::class)->getUser($email);
        if (User::isValidUser($user)){
            $this->generateChangePassword($user);
            return true;
        }
        return false;
    }

    private function generateChangePassword(User $user){
        $password = base64_encode(random_bytes(10));
        $this->container->get(UserProvider::class)->changePassword($user, $password);
        $this->sendChangePasswordMail($user->getEmail(), $password);
    }


    private function sendChangePasswordMail(string $email, string $newPassword){
        $mailer = $this->container->get(UsersMailer::class);
        $info = array('password' => $newPassword);
        $mailer->sendMessage('Password Change Email', 'fea.ortenore@gmail.com',
            'user/change.html.twig', $info);#$user->getEmail()
    }

    private function getEmailFromForm(Request $request) {
        if ( $request->getMethod() == Request::METHOD_POST ) {
            return $request->get('email');
        }
        return null;
    }
}