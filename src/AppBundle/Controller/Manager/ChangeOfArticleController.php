<?php

namespace AppBundle\Controller\Manager;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
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
    public function changeAction($id, Request $request, EntityManager $em)
    {
        $form = $this->createChangeForm($request, $em, $id);
        if(!$form){
            return $this->render(
                'error/error.html.twig',
                array(
                    'label'=>"Can't find article with id :".$id,
                    'category_root'=>$em->getRepository(Category::class)->getCategoryRoot(),
                    ));
        }
        if ($this->tryChangeArticle($form, $em)) {
            return $this->redirectToRoute('article', array('id'=>$id));
        }
        return $this->render(
            'main/change.html.twig',
            array('form' => $form->createView(),
                'category_root'=>$em->getRepository(Category::class)->getCategoryRoot(),
            ));

    }

    /**
     * @Route("/add_similar/{id}", name="add_similar")
     */
    public function similarAction($id, Request $request, EntityManager $em)
    {
        $similar = $request->request->get('similar');
        $repository = $em->getRepository(Article::class);
        $article = $repository->find($id);
        if (!$article){
            return $this->render(
                'error/error.html.twig',
                array('label'=>"cant_find_article".$id,
                'category_root'=>$em->getRepository(Category::class)->getCategoryRoot(),
                ));
        }
        if($similar && $article && ($similar = $repository->findOneBy(array('name'=>$similar)))){
            $article->addSimilarArticle($similar);
            $em->flush();
        }
        return $this->redirectToRoute('article', array('id'=>$id));

    }
    private function createChangeForm(Request $request, EntityManager $em, $id)
    {
        $this->article = $em->getRepository(Article::class)->find($id);
        if (!$this->article){
            return null;
        }
        $form = $this->createForm(ArticleAddChangeForm::class, $this->article);
        $form->handleRequest($request);
        return $form;
    }

    private function tryChangeArticle(Form $form, EntityManager $em):bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $em->getRepository(Article::class);
            $repository->articleChanged();
            return true;
        }
        return false;
    }
}