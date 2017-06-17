<?php
/**
 * Created by PhpStorm.
 * User: RMV
 * Date: 17.06.2017
 * Time: 10:07
 */

namespace AppBundle\Controller\User;


use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShowArticlesListController extends Controller
{
    /**
     * @Route("/show", name="show_articles")
     */
    public function showAllAction(EntityManager $em)
    {
        $repository = $em->getRepository(Article::class);
        $articles = $repository->getAllArticles('date');
        return $this->render('main/main.html.twig', array(
        ));
    }

    /**
     * @Route("/find", name="find_article")
     */
    public function findAction(EntityManager $em, Request $request)
    {
        return $this->render('main/main.html.twig', array(
        ));
    }

    /**
     * @Route("/category/{id}", name="category_articles")
     */
    public function getCategoryAction($id, EntityManager $em)
    {
        $repository = $em->getRepository(Article::class);
        $category = $em->getRepository(Category::class)->find($id);
        $categories = $this->getSubCategories($category);
        $articles = $repository->getAllArticlesInCategory($categories, 'date');
        return $this->render('main/main.html.twig', array(
        ));
    }

    private function getSubCategories(Category $category){
        $categories = array();
        $sub = array($category);
        while (sizeof($sub)!=0){
            foreach ($sub[0]->getChilds() as $child){
                $sub[] = $child;
                $categories[] = $child->getId();
            }
            unset($sub[0]);
        }
        return $categories;
    }
}