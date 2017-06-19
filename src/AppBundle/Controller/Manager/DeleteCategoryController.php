<?php
/**
 * Created by PhpStorm.
 * User: RMV
 * Date: 15.06.2017
 * Time: 8:20
 */

namespace AppBundle\Controller\Manager;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DeleteCategoryController extends Controller
{
    /**
     * @Route("/delete_category/{id}", name="delete_category")
     */
    public function deleteAction($id, Request $request, EntityManager $em)
    {
        if ($this->deleteCategory($id, $em)) {
            return $this->redirectToRoute('main');
        }
        return $this->render(
            'error/error.html.twig',
            array(
                'label'=>"cant_find_category".$id,
                'category_root'=>$em->getRepository(Category::class)->getCategoryRoot(),
                ));
    }

    private function deleteCategory($id, EntityManager $em):bool
    {
        $repository = $em->getRepository(Category::class);
        return $repository->deleteCategory($id);
    }
}