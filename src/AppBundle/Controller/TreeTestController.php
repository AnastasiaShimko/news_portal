<?php
/**
 * Created by PhpStorm.
 * User: RMV
 * Date: 18.06.2017
 * Time: 19:25
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TreeTestController extends Controller
{
    /**
     * @Route("/test", name="test")
     */
    public function controlAction(EntityManager $em){
        return $this->render(
            'main/category_tree.html.twig',
            array(
                'category_root'=>$em->getRepository(Category::class)->getCategoryRoot())
        );
    }
}