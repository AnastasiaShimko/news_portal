<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="child")
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $childs;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="category")
     */
    private $articles;

    public function __construct()
    {
        $this->childs = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId():int
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addChild(Category $child)
    {
        $this->childs[] = $child;
        return $this;
    }

    public function removeChild(Category $child)
    {
        $this->childs->removeElement($child);
    }

    public function getChilds()
    {
        return $this->childs;
    }

    public function addArticle(Article $article)
    {
        $this->articles[] = $article;
        return $this;
    }

    public function removeArticle(Article $article)
    {
        $this->articles->removeElement($article);
    }

    public function getArticles()
    {
        return $this->articles;
    }

    public function getChildsList()
    {
        if ( $this->childs->count()>0) {
            return $this->childs->getValues();
        }
        return null;
    }
}
