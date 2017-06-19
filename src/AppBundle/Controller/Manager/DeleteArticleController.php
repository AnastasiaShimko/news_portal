<?php

namespace AppBundle\Controller\Manager;

use AppBundle\Entity\Article;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DeleteArticleController extends Controller
{
    /**
     * @Route("/delete_article/{id}", name="delete_article")
     */
    public function deleteAction($id, EntityManager $em)
    {
        if($this->deleteArticle($em, $id)) {
            return $this->redirectToRoute('main');
        }
        return $this->render(
            'error/error.html.twig',
            array('label'=>"Can't find article with id :".$id)
        );
    }

    private function deleteArticle(EntityManager $em, int $id):bool
    {
        $repository = $em->getRepository(Article::class);
        $article = $repository->find($id);
        if($article) {
            $repository->delArticle($repository->find($id));
            return true;
        }
        return false;
    }
}