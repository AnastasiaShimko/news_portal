<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Category;
use AppBundle\Entity\ChangedUser;
use AppBundle\Form\ChangeUserForm;
use AppBundle\Provider\UserProvider;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ChangeUserController extends Controller
{
    private $userInForm;

    /**
     * @Route("/change_user", name="change_user")
     */
    public function changeAction(Request $request, AuthenticationUtils $authUtils, EntityManager $em)
    {
        $form = $this->createChangeForm($request);
        if($this->tryChangeUser($form)){
            return $this->redirectToRoute('main');
        }
        return $this->render('main/change_user.html.twig', array(
                'form' => $form->createView(),
                'category_root'=>$em->getRepository(Category::class)->getCategoryRoot(),
            )
        );
    }

    private function tryChangeUser(Form $form):bool
    {
        $userProvider = $this->container->get(UserProvider::class);
        return $form->isSubmitted() && $form->isValid() &&
            $userProvider->checkPasswordChangeUser($this->userInForm, $this->getUser());
    }

    private function createChangeForm(Request $request):Form
    {
        $this->userInForm = new ChangedUser();
        $this->userInForm->setNotification($this->getUser()->getNotification());
        $form = $this->createForm(ChangeUserForm::class, $this->userInForm);
        $form->handleRequest($request);
        return $form;
    }
}