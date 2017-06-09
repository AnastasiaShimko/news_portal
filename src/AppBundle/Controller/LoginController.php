<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="user_login")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $user = new User();
        $form = $this->createForm(LoginForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $repository = $em->getRepository('AppBundle:User');
            $user = $repository->findOneBy(
                array('email' => $user->getEmail(), 'password' => $password)
            );
            if ($user){
                return $this->render(
                    'user/login.html.twig',
                    array('form' => $form->createView())
                );
            }
            return $this->redirectToRoute('user_registration');
        }
        return $this->render(
            'user/login.html.twig',
            array('form' => $form->createView())
        );
    }
}