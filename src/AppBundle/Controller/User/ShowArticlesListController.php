<?php

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
        $query = $repository->createQueryBuilder('a');
        $this->orderByParam($query);
        return $this->renderPaginator($query, $request, $em);
    }

    /**
     * @Route("/find", name="find_article")
     */
    public function findAction(EntityManager $em, Request $request)
    {
        $search = $request->query->get("find");
        if($search) {
            $repository = $em->getRepository(Article::class);
            $query = $repository->createQueryBuilder('a');
            $this->searchByPart($query, $search);
            $this->orderByParam($query);
            return $this->renderPaginator($query, $request, $em);
        }
        return $this->redirectToRoute('show_articles');
    }

    private function searchByPart(QueryBuilder $query, string $search)
    {
        $query->andWhere('a.name LIKE :search');
        $query->orWhere('a.author LIKE :search');
        $query->orWhere('a.annotation LIKE :search');
        $search = '%' . $search . '%';
        $query->setParameter('search', $search);
    }

    /**
     * @Route("/category/{id}", name="category_articles")
     */
    public function getCategoryAction($id, EntityManager $em, Request $request)
    {
        $repository = $em->getRepository(Article::class);
        $query = $repository->createQueryBuilder('a');
        $this->whereCategory($query, $em, $id);
        $this->orderByParam($query);
        return $this->renderPaginator($query, $request, $em);
    }

    public function whereCategory(QueryBuilder $query, EntityManager $em, $id)
    {
        $category = $em->getRepository(Category::class)->find($id);
        $query->where('a.category = '.$category->getId());
        $categories = $this->getSubCategories($category);
        foreach ($categories as $idCat){
            $query->orWhere('a.category = '.$idCat);
        }
    }

    public function orderByParam(QueryBuilder $query)
    {
        $query->orderBy('a.'.$this->getUser()->getParameters()->getOrderBy(), 'DESC');
    }

    private function divSearchString(string $find){
        $parts = explode(",", $find);
        foreach ($parts as $part){
            trim($part);
        }
        return $parts;
    }

    private function getSubCategories(Category $category):array
    {
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

   private function renderPaginator(QueryBuilder $query, Request $request,  EntityManager $em )
   {
       $paginator  = $this->get('knp_paginator');
       $pagination = $paginator->paginate(
           $query,
           $request->query->getInt('page', 1),
           $this->getUser()->getParameters()->getArticleCount()
       );
       return $this->render('main/list.html.twig', array('pagination' => $pagination,
           'category_root'=>$em->getRepository(Category::class)->getCategoryRoot(),
           ));
   }

}