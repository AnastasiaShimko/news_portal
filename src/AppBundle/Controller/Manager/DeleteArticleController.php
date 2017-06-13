<?php

namespace AppBundle\Controller\Manager;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleAddChangeForm;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class DeleteArticleController extends Controller
{
    private $article;

    /**
     * @Route("/delete_article/{id}", name="delete_article")
     */
    public function registerAction($id, EntityManager $em)
    {
        $this->deleteArticle($em, $id);
        return $this->redirectToRoute('main');
    }

    private function deleteArticle(EntityManager $em, $id){
        $repository = $em->getRepository(Article::class);
        $repository->delArticle($repository->find($id));
    }
}