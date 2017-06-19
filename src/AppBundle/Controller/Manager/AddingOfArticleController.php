<?php

namespace AppBundle\Controller\Manager;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleAddChangeForm;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class AddingOfArticleController extends Controller
{
    private $article;

    /**
     * @Route("/new_article", name="new_article")
     */
    public function addAction(Request $request, EntityManager $em)
    {
        $form = $this->createAddingForm($request);
        if ($this->tryAddArticle($form, $em)) {
            return $this->redirectToRoute('article', array('id'=>$this->article->getId()));
        }
        return $this->render(
            'main/add.html.twig',
            array('form' => $form->createView())
        );
    }

    private function createAddingForm(Request $request):Form
    {
        $this->article = new Article();
        $form = $this->createForm(ArticleAddChangeForm::class, $this->article);
        $form->handleRequest($request);
        return $form;
    }

    private function tryAddArticle(Form $form, EntityManager $em):bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $em->getRepository(Article::class);
            $repository->addArticle($this->article);
            return true;
        }
        return false;
    }
}