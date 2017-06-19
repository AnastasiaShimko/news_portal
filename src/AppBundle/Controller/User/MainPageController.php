<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainPageController extends Controller
{
    /**
     * @Route("/main", name="main")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils, EntityManager $em)
    {
        return $this->render('main/main.html.twig', array(
            'category_root'=>$em->getRepository(Category::class)->getCategoryRoot(),
        ));
    }
}