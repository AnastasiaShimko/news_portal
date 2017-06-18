<?php

namespace AppBundle\Controller\User;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleShowController extends Controller
{
    /**
     * @Route("/article/{id}", name="article")
     */
    public function loginAction($id, EntityManager $em)
    {
        $repos = $em->getRepository('AppBundle:Article');
        $article = $repos->find($id);
        $article->increaseVisitorCount();
        $em->flush();
        return $this->render('main/show_article.html.twig', array(
                'article' => $article,
            )
        );
    }
}