<?php
/**
 * Created by PhpStorm.
 * User: RMV
 * Date: 18.06.2017
 * Time: 7:37
 */

namespace AppBundle\Controller\User;


use AppBundle\Entity\UserParameters;
use AppBundle\Provider\UserProvider;
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
        if($orderBy && $articleCount){
            $parameters = $this->getUser()->getParameters();
            $parameters->setOrderBy($orderBy);
            $parameters->setArticleCount($articleCount);
            $em->flush();
        }
        return $this->render('main/main.html.twig');
    }
}