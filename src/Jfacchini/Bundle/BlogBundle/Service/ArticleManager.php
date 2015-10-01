<?php

namespace Jfacchini\Bundle\BlogBundle\Service;

use Jfacchini\Bundle\BlogBundle\Entity\Article;

class ArticleManager
{
    /** @var CommentManager */
    private $commentManager;

    public function setCommentManager(CommentManager $commentManager)
    {
        $this->commentManager = $commentManager;
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
     * Add a new comment into
     *
     * @param Article $article
     * @param $comment
     */
    public function addNewComment(Article $article, $comment)
    {
        $article->addComment(
            $this->commentManager->create($comment)
        );
    }
}