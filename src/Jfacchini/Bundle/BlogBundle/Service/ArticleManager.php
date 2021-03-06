<?php

namespace Jfacchini\Bundle\BlogBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Jfacchini\Bundle\BlogBundle\Entity\Article;
use Jfacchini\Bundle\BlogBundle\Entity\Comment;
use Jfacchini\Bundle\BlogBundle\Entity\Rate;
use Swift_Mailer;
use Swift_Message;

class ArticleManager
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var Swift_Mailer */
    private $mailer;

    /** @var CommentManager */
    private $commentManager;

    /** @var RateManager */
    private $rateManager;

    public function __construct(EntityManagerInterface $em, Swift_Mailer $mailer)
    {
        $this->em     = $em;
        $this->mailer = $mailer;
    }

    /**
     * @param CommentManager $commentManager
     */
    public function setCommentManager(CommentManager $commentManager)
    {
        $this->commentManager = $commentManager;
    }

    /**
     * @param RateManager $rateManager
     */
    public function setRateManager(RateManager $rateManager)
    {
        $this->rateManager = $rateManager;
    }

    /**
     * Create a new article with a given title and content
     *
     * @param Article $article
     */
    public function create(Article $article)
    {
        // The created date can be set on PrePersist event
        // but this is an example for instructions that can be done while creating the Object
        $article->setCreatedDate(new \DateTime());
        $this->em->persist($article);
    }

    /**
     * Add a new comment to an article
     *
     * @param Article $article
     * @param int $comment
     */
    public function addNewComment(Article $article, Comment $comment)
    {
        $article->addComment($comment);
        //TODO: Use cascade persist into article instead
        $this->commentManager->create($comment);
    }

    /**
     * Add a new rate to an article
     *
     * @param Article $article
     * @param int $value
     */
    public function addNewRate(Article $article, Rate $rate)
    {
        $article->addRate($rate);
        //TODO: Use cascade persist into article instead
        $this->rateManager->create($rate);
    }

    /**
     * Get average rates of an article, returns null if no rate
     *
     * @param Article $article
     * @return float|null
     */
    public function getRatesAverage(Article $article)
    {
        $sum = 0;
        $nbRates = $article->getRates()->count();

        /** @var Rate $rate */
        foreach ($article->getRates() as $rate) {
            $sum += $rate->getValue();
        }

        return $nbRates > 0 ? $sum / $nbRates : null;
    }

    /**
     * Retrieve an article with all its comments
     *
     * @param $id
     */
    public function findArticleByIdWithAllComments($id)
    {
        return $this->em
            ->getRepository('BlogBundle:Article')
            ->findByIdWithAllCommentsQuery($id)
            ->getResult()
        ;
    }

    public function findAll()
    {
        return $this->em
            ->getRepository('BlogBundle:Article')
            ->findAll()
        ;
    }

    /**
     * Get an article with its last comments for 24 hours
     *
     * @param int $id Article id
     * @return Article|null Article or null if not found
     */
    public function findArticleByIdWithCommentsFor24Hours($id)
    {
        return $this->em
            ->getRepository('BlogBundle:Article')
            ->findArticleByIdWithCommentsFor24HoursQuery($id)
            ->getOneOrNullResult()
        ;
    }

    /**
     * Send a notification mail to the author
     *
     * @param Article $article
     */
    public function sendNotifyMailWithComments(Article $article)
    {
        $message = Swift_Message::newInstance()
            ->setSubject('Article notifications')
            ->setFrom('contact@domain.tld')
            ->setTo($article->getAuthorEmail())
            //TODO: setup a mail template
            ->setBody(sprintf('Hello, you have %d comment(s) for the last 24 hours for the article "%s"',
                $article->getComments()->count(),
                $article->getTitle()
            ))
        ;

        $this->mailer->send($message);
    }
}