<?php

namespace Jfacchini\Bundle\BlogBundle\Service;

use Jfacchini\Bundle\BlogBundle\Entity\Article;
use Jfacchini\Bundle\BlogBundle\Entity\Rate;

class ArticleManager
{
    /** @var CommentManager */
    private $commentManager;

    /** @var RateManager */
    private $rateManager;

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
     * @param  string $title   Article title
     * @param  string $content Article content
     * @return Article
     */
    public function create($title, $content)
    {
        return (new Article())
            ->setTitle($title)
            ->setContent($content)
        ;
    }

    /**
     * Add a new comment to an article
     *
     * @param Article $article
     * @param int $comment
     */
    public function addNewComment(Article $article, $comment)
    {
        $article->addComment(
            $this->commentManager->create($comment)
        );
    }

    /**
     * Add a new rate to an article
     *
     * @param Article $article
     * @param int $value
     */
    public function addNewRate(Article $article, $value)
    {
        $article->addRate(
            $this->rateManager->create($value)
        );
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
}