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

use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShowArticlesListController extends Controller
{
    /**
     * @Route("/show", name="show_articles")
     */
    public function showAllAction(EntityManager $em, Request $request)
    {
        $repository = $em->getRepository(Article::class);
        $query = $repository->createQueryBuilder('a')
        ->orderBy('a.date', 'ASC');
        return $this->renderPaginator($query, $request);
    }

    /**
     * @Route("/find", name="find_article")
     */
    public function findAction(EntityManager $em, Request $request)
    {
        $repository = $em->getRepository(Article::class);
        $query = $repository->createQueryBuilder('a');
        $category = $em->getRepository(Category::class)->find($id);
        $query->where('a.category = '.$category->getId());
        $categories = $this->getSubCategories($category);
        foreach ($categories as $idCat){
            $query->orWhere('a.category = '.$idCat);
        }
        $query->orderBy('a.date', 'ASC');
        return $this->renderPaginator($query, $request);
    }

    /**
     * @Route("/category/{id}", name="category_articles")
     */
    public function getCategoryAction($id, EntityManager $em, Request $request)
    {
        $repository = $em->getRepository(Article::class);
        $query = $repository->createQueryBuilder('a');
        $category = $em->getRepository(Category::class)->find($id);
        $query->where('a.category = '.$category->getId());
        $categories = $this->getSubCategories($category);
        foreach ($categories as $idCat){
            $query->orWhere('a.category = '.$idCat);
        }
        $query->orderBy('a.date', 'ASC');
        return $this->renderPaginator($query, $request);
    }



    private function getSubCategories(Category $category){
        $categories = array();
        $sub = array($category);
        while (sizeof($sub)!=0){
            foreach (array_shift($sub)->getChilds() as $child){
                $sub[] = $child;
                $categories[] = $child->getId();
            }
        }
        return $categories;
    }


   private function renderPaginator(QueryBuilder $query, Request $request){
       $paginator  = $this->get('knp_paginator');
       $pagination = $paginator->paginate(
           $query,
           $request->query->getInt('page', 1),
           5
       );
       return $this->render('main/list.html.twig', array('pagination' => $pagination));
   }

}