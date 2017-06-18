<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="articles_full_text")
 * @ORM\Entity
 */
class ArticleFullText
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $fullText;

    public function getId():int
    {
        return $this->id;
    }

    public function setFullText(string $fullText)
    {
        $this->fullText = $fullText;
        return $this;
    }

    public function getFullText()
    {
        return $this->fullText;
    }
}
