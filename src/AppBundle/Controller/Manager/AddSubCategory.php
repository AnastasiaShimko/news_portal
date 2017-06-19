<?php

namespace AppBundle\Controller\Manager;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryAddForm;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class AddSubCategory extends Controller
{
    private $category;

    /**
     * @Route("/new_category/{id}", name="new_category")
     */
    public function addAction($id, Request $request, EntityManager $em)
    {
        $form = $this->createAddForm($request, $em, $id);
        if(!$form){
            return $this->render(
                'error/error.html.twig',
                array('label'=>"Can't find category with id :".$id)
            );
        }
        if ($this->tryAddCategory($form, $em)) {
            return $this->redirectToRoute('main');
        }
        return $this->render(
            'main/add_category.html.twig',
            array('parent'=>$this->category->getParent()->getName(),
                'form' => $form->createView(),
                'category_root'=>$em->getRepository(Category::class)->getCategoryRoot()
            ));
    }

    private function createAddForm(Request $request, EntityManager $em, $id)
    {
        $this->category = new Category();
        $parent = $em->getRepository(Category::class)->find($id);
        if(!$parent){
            return null;
        }
        $this->category->setParent($parent);
        $form = $this->createForm(CategoryAddForm::class, $this->category);
        $form->handleRequest($request);
        return $form;
    }

    private function tryAddCategory(Form $form, EntityManager $em):bool{
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $em->getRepository(Category::class);
            $repository->addCategory($this->category);
            return true;
        }
        return false;
    }
}