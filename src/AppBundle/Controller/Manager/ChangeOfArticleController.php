<?php

namespace AppBundle\Controller\Manager;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleAddChangeForm;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class ChangeOfArticleController extends Controller
{
    private $article;

    /**
     * @Route("/change_article/{id}", name="change_article")
     */
    public function registerAction($id, Request $request, EntityManager $em)
    {
        $form = $this->createChangeForm($request, $em, $id);
        if ($this->tryChangeArticle($form, $em)) {
            return $this->redirectToRoute('article', array('id'=>$id));
        }
        return $this->render(
            'main/change.html.twig',
            array('form' => $form->createView())
        );

    }

    private function createChangeForm(Request $request, EntityManager $em, $id){
        $this->article = $em->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleAddChangeForm::class, $this->article);
        $form->handleRequest($request);
        return $form;
    }

    private function tryChangeArticle(Form $form, EntityManager $em){
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $em->getRepository(Article::class);
            $repository->articleChanged();
            return true;
        }
        return false;
    }
}