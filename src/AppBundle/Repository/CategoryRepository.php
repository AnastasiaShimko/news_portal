<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function getCategoryRoot(){
        return $this->find(1);
    }

    public function deleteCategory($id)
    {
        if ($id != 1) {
            $category = $this->find($id);
            if ($category) {
                $parent = $category->getParent();
                $parent->removeChild($category);
                foreach ($category->getChilds() as $child){
                   $parent->addChild($child);
                   $child->setPerent($parent);
                }
                $this->_em->remove($category);
                $this->_em->flush();
            }
        }
    }

    public function addCategory(Category $category)
    {
        $this->_em->persist($category);
        $this->_em->flush();
    }
}
