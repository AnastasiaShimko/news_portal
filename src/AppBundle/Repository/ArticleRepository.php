<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getTopNews():array
    {
        $query = $this->createQueryBuilder('a');
        $query->where('a.date >= :current');
        $query->setParameter('current', new \DateTime('-7 day'), Type::DATETIME);
        $query->orderBy('a.visitorCount', 'DESC');
        $query->setMaxResults(10);
        return $query->getQuery()->execute();
    }

    public function addArticle(Article $article)
    {
        $article->setDate(new \DateTime());
        $this->_em->persist($article->getFullText());
        $this->_em->persist($article);
        $this->_em->flush();
    }

    public function delArticle(Article $article)
    {
        foreach ($article->getSimilarArticles() as $art) {
            $article->removeSimilarArticle($art);
        }
        $this->_em->persist($article);
        $this->_em->flush();
        $this->_em->remove($article->getFullText());
        $this->_em->remove($article);
        $this->_em->flush();
    }

    public function articleChanged()
    {
        $this->_em->flush();
    }
}
