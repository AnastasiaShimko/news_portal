<?php

namespace AppBundle\Controller\User;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ParametersController extends Controller
{
    /**
     * @Route("/parameter", name="parameter")
     */
    public function parameterAction(Request $request, EntityManager $em)
    {
        $orderBy = $request->request->get('orderBy');
        $articleCount = $request->request->get('articleCount');
        $this->changeParameters($orderBy, $articleCount, $em);
        return $this->render('main/params.html.twig');
    }

    public function changeParameters(string $orderBy, int $articleCount, EntityManager $em)
    {
        if($orderBy && $articleCount){
            $parameters = $this->getUser()->getParameters();
            $parameters->setOrderBy($orderBy);
            $parameters->setArticleCount($articleCount);
            $em->flush();
        }
    }
}