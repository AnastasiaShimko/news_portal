<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function addArticle(Article $article){
        $article->setDate(new \DateTime());
        $this->_em->persist($article->getFullText()); //if it's nessesery
        $this->_em->persist($article);
        $this->_em->flush();
    }

    public function delArticle(Article $article){
        $this->_em->remove($article->getFullText()); //if it's nessesery
        $this->_em->remove($article);
        $this->_em->flush();
    }

    public function articleChanged(){
        $this->_em->flush();
    }
}
