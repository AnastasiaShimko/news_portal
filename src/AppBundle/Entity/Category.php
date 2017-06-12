<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="categories")
 * @ORM\Entity
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->childs = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Category $parent
     *
     * @return Category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param Category $child
     *
     * @return Category
     */
    public function addChild(Category $child)
    {
        $this->childs[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param Category $child
     */
    public function removeChild(Category $child)
    {
        $this->childs->removeElement($child);
    }

    /**
     * Get childs
     *
     * @return Collection
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Add article
     *
     * @param Article $article
     *
     * @return Category
     */
    public function addArticle(Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param Article $article
     */
    public function removeArticle(Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }
}
