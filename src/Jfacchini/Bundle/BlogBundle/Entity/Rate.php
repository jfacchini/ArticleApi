<?php

namespace Jfacchini\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rate
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Rate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="integer")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="int")
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThanOrEqual(5)
     */
    private $value;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Jfacchini\Bundle\BlogBundle\Entity\Article", inversedBy="rates")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;

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
     * Set value
     *
     * @param integer $value
     *
     * @return Rate
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     * @return Rate
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }
}

