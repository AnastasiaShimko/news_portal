<?php
/**
 * Created by PhpStorm.
 * User: RMV
 * Date: 11.06.2017
 * Time: 19:16
 */

namespace AppBundle\Services;


use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Mailer
{
    public function sendRegistrationMail(\Swift_Mailer $mailer, User $user, \Twig_Environment $twig){
        $message = $this->createMessage('Confirm Registration', 'fea.ortenore@gmail.com');#$user->getEmail()
        $info =  array('id' => md5($user->getId().$user->getPassword().$user->getEmail()));
        $this->setBody('user/registration.html.twig', $info, $message, $twig);
        $mailer->send($message);
    }

    public function sendChangePasswordMail(\Swift_Mailer $mailer, string $email, string $newPassword, \Twig_Environment $twig){

        $message = $this->createMessage('Password Change Email', 'fea.ortenore@gmail.com');#$email
        $info = array('password' => $newPassword);
        $this->setBody('user/change.html.twig', $info, $message, $twig);
        $mailer->send($message);
    }

    protected function setBody(string $twig, array $info, \Swift_Message $message, \Twig_Environment $twigEnvironment){
        $message->setBody(
            $twigEnvironment->render(
                $twig,
                $info
            ),
            'text/html'
        );
    }

    protected function createMessage(string $subject, string $addressTo){
        $message = new \Swift_Message($subject);
        $message->setFrom('shimkoanastasia@gmail.com');
        $message->setTo($addressTo);
        return $message;
    }
}
