<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_params")
 * @ORM\Entity
 */
class UserParameters
{
    public function __construct()
    {
        $this->articleCount = 10;
        $this->orderBy = 'date';
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $articleCount;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $orderBy;

    public function getId():int
    {
        return $this->id;
    }

    public function setArticleCount(int $articleCount)
    {
        $this->articleCount = $articleCount;
        return $this;
    }

    public function getArticleCount():int
    {
        return $this->articleCount;
    }

    public function setOrderBy(string $orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function getOrderBy():string
    {
        return $this->orderBy;
    }
}
