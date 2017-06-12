<?php

namespace AppBundle\Service;


class UsersMailer
{

    private $mailer;
    private $twig;
    private $adminEmail;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, string $adminEmail)
    {
        $this->mailer=$mailer;
        $this->twig = $twig;
        $this->adminEmail = $adminEmail;
    }

    public function sendMessage(string $subject, string $addressTo, string $twigName, array $info){
        $message = $this->createMessage($subject, $addressTo);#$addressTo
        $this->setBody($twigName, $info, $message);
        $this->mailer->send($message);
    }

    private function setBody(string $twig, array $info, \Swift_Message $message){
        $message->setBody(
            $this->twig->render(
                $twig,
                $info
            ),
            'text/html'
        );
    }

    private function createMessage(string $subject, string $addressTo){
        $message = new \Swift_Message($subject);
        $message->setFrom($this->adminEmail);
        $message->setTo($addressTo);
        return $message;
    }
}
