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
    public function registerAction($id, EntityManager $em)
    {
        $this->deleteArticle($em, $id);
        return $this->redirectToRoute('main');
    }

    private function deleteArticle(EntityManager $em, int $id):bool
    {
        $repository = $em->getRepository(Article::class);
        return $repository->delArticle($repository->find($id));
    }
}