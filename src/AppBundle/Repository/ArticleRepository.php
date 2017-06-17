<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function addArticle(Article $article){
        $article->setDate(new \DateTime());
        $this->_em->persist($article->getFullText());
        $this->_em->persist($article);
        $this->_em->flush();
    }

    public function delArticle(Article $article){
        $this->_em->remove($article->getFullText());
        $this->_em->remove($article);
        $this->_em->flush();
    }

    public function articleChanged(){
        $this->_em->flush();
    }

    public function getAllArticles(string $orderBy){
        return $this->findBy(array(), array($orderBy => 'ASC'));
    }

    public function getAllArticlesInCategory(array $categories, string $orderBy){
        return $this->findBy(array('category' => $categories), array($orderBy => 'ASC'));
    }
}
