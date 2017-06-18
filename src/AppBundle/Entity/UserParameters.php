<?php
/**
 * Created by PhpStorm.
 * User: RMV
 * Date: 18.06.2017
 * Time: 7:32
 */

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
     * Set articleCount
     *
     * @param integer $articleCount
     *
     * @return UserParameters
     */
    public function setArticleCount($articleCount)
    {
        $this->articleCount = $articleCount;

        return $this;
    }

    /**
     * Get articleCount
     *
     * @return integer
     */
    public function getArticleCount()
    {
        return $this->articleCount;
    }

    /**
     * Set orderBy
     *
     * @param string $orderBy
     *
     * @return UserParameters
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Get orderBy
     *
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }
}
