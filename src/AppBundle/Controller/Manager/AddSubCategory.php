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
    public function registerAction($id, Request $request, EntityManager $em)
    {
        $form = $this->createAddForm($request, $em, $id);
        if ($this->tryAddCategory($form, $em)) {
            return $this->redirect($request->server->get('HTTP_REFERER'));
        }
        return $this->render(
            'main/add_category.html.twig',
            array('parent'=>$this->category->getParent()->getName(),'form' => $form->createView())
        );
    }

    private function createAddForm(Request $request, EntityManager $em, $id):Form{
        $this->category = new Category();
        $this->category->setParent($em->getRepository(Category::class)->find($id));
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