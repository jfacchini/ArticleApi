<?php

namespace Jfacchini\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Jfacchini\Bundle\BlogBundle\Repository\ArticleRepository")
 */
class Article
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     *
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="date")
     */
    private $createdDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Jfacchini\Bundle\BlogBundle\Entity\Comment", mappedBy="article")
     */
    private $comments;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Jfacchini\Bundle\BlogBundle\Entity\Rate", mappedBy="article")
     */
    private $rates;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->rates    = new ArrayCollection();
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
     * Set string
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get string
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param \DateTime $date
     * @return Article
     */
    public function setCreatedDate(\DateTime $date)
    {
        $this->createdDate = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Add a new comment
     *
     * @param Comment $comment
     * @return Article
     */
    public function addComment(Comment $comment)
    {
        $comment->setArticle($this);
        $this->comments->add($comment);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add a new Rate
     *
     * @param Rate $rate
     * @return Article
     */
    public function addRate(Rate $rate)
    {
        $rate->setArticle($this);
        $this->rates->add($rate);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRates()
    {
        return $this->rates;
    }
}
