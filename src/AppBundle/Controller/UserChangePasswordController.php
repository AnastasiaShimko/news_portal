<?php
namespace AppBundle\Controller;


use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserChangePasswordController extends Controller
{

    /**
     * @Route("/password_change", name="password_change")
     */
    public function changeAction(EntityManager $em,  UserPasswordEncoderInterface $passwordEncoder,
                                 \Swift_Mailer $mailer, Request $request)
    {
        $email = $this->getEmailFromForm($request);
        if ($this->isValidEmail($email, $em)) {
            $password = base64_encode(random_bytes(10));
            $this->changePassword($em, $email, $password, $passwordEncoder);
            $this->sendMail($mailer, $email, $password);
            return $this->redirectToRoute('login');
        }
        return $this->render('user/email.html.twig');
    }

    private function isValidEmail($email, EntityManager $em){
        if ($email){
            $repository = $em->getRepository('AppBundle:User');
            $user =  $repository->findOneBy(array('email' => $email));
            if ($user && $user->getRole()!=0){
                return true;
            }
        }
        return false;
    }

    private function changePassword(EntityManager $em, string $email, string $password,
                                    UserPasswordEncoderInterface $passwordEncoder){
        $repository = $em->getRepository('AppBundle:User');
        $user = $repository->findOneBy(array('email' => $email));
        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        $em->flush();
    }

    private function sendMail(\Swift_Mailer $mailer, string $email, string $newPassword){
        $message = new \Swift_Message('Password Change Email');
        $message->setFrom('shimkoanastasia@gmail.com');
        $message->setTo('fea.ortenore@gmail.com'); #$email
        $message->setBody(
            $this->renderView(
                'user/change.html.twig',
                array('password' => $newPassword)
            ),
            'text/html'
        );
        $mailer->send($message);
    }

    public function getEmailFromForm(Request $request) {
        if ( $request->getMethod() == Request::METHOD_POST ) {
            return $request->get('email');
        }
        return null;
    }
}