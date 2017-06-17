<?php
/**
 * Created by PhpStorm.
 * User: RMV
 * Date: 15.06.2017
 * Time: 8:20
 */

namespace AppBundle\Controller\Manager;


use AppBundle\Entity\Category;
use AppBundle\Form\CategoryAddForm;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class DeleteCategoryController extends Controller
{
    /**
     * @Route("/delete_category/{id}", name="delete_category")
     */
    public function registerAction($id, Request $request, EntityManager $em)
    {

        if ($this->deleteCategory($em, $id)) {
            return $this->redirectToRoute('main');
        }
        return $this->render(
            'error/error.html.twig',
            array('label'=>"Can't find category with id ".$id)
        );

    }

    private function deleteCategory($id, EntityManager $em){
        $repository = $em->getRepository(Category::class);
        $repository->deleteCategory($id);
    }
}