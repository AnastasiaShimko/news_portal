<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="articles_main_part")
 * @UniqueEntity(fields="name", message="Name already taken")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="integer")
     */
    private $visitorCount;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $author;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $annotation;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="ArticleFullText")
     * @ORM\JoinColumn(name="full_text_id", referencedColumnName="id")
     */
    private $fullText;

    /**
     * Many Users have many Users.
     * @ORM\ManyToMany(targetEntity="Article")
     * @ORM\JoinTable(name="similar_articles",
     *      joinColumns={@ORM\JoinColumn(name="articles_id", referencedColumnName="id")}
     *      )
     */
    private $similarArticles;

    public function __construct()
    {
        $this->similarArticles = new ArrayCollection();
        $this->visitorCount = 0;
        $this->fullText = new ArticleFullText();
    }

    public function increaseVisitorCount(){
        $this->visitorCount++;
    }

    public function getText()
    {
        return $this->fullText->getFullText();
    }

    public function setText(string $text)
    {
        $this->fullText->setFullText($text);
    }

    public function getId():int
    {
        return $this->id;
    }

    public function setVisitorCount(int $visitorCount)
    {
        $this->visitorCount = $visitorCount;

        return $this;
    }

    public function getVisitorCount():int
    {
        return $this->visitorCount;
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

    public function setAuthor(string $author)
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDate():\DateTime
    {
        return $this->date;
    }

    public function setAnnotation(string $annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }

    public function getAnnotation()
    {
        return $this->annotation;
    }

    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setFullText(ArticleFullText $fullText = null)
    {
        $this->fullText = $fullText;

        return $this;
    }

    public function getFullText()
    {
        return $this->fullText;
    }

    public function addSimilarArticle(Article $similarArticle)
    {
        if($this->similarArticles->count()<5) {
            $this->similarArticles[] = $similarArticle;
        }
        return $this;
    }

    public function removeSimilarArticle(Article $similarArticle)
    {
        $this->similarArticles->removeElement($similarArticle);
    }

    public function getSimilarArticles()
    {
        return $this->similarArticles;
    }
}
