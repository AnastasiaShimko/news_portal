<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function getCategoryRoot()
    {
        return $this->find(1);
    }

    public function deleteCategory($id):bool
    {
        if ($id != 1) {
            $category = $this->find($id);
            if ($category) {
                $this->changeArticleCategory($category);
                $this->moveChildCategory($category);
                $this->_em->remove($category);
                $this->_em->flush();
                return true;
            }
        }
        return false;
    }

    private function changeArticleCategory(Category $category)
    {
        $repository = $this->_em->getRepository(Article::class);
        $articles = $repository->findBy(array('category'=>$category->getId()));
        foreach ($articles as $article){
            $article->setCategory($category->getParent());
        }
    }

    private function moveChildCategory(Category $category)
    {
        $parent = $category->getParent();
        $parent->removeChild($category);
        foreach ($category->getChilds() as $child){
            $child->setParent($parent);
        }
    }

    public function addCategory(Category $category)
    {
        $this->_em->persist($category);
        $this->_em->flush();
    }
}
