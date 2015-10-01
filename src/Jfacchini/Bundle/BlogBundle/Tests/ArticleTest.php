<?php

namespace Jfacchini\Bundle\BlogBundle\Tests;

use Jfacchini\Bundle\BlogBundle\Entity\Article;
use Jfacchini\Bundle\BlogBundle\Entity\Comment;
use Jfacchini\Bundle\BlogBundle\Service\ArticleManager;
use Jfacchini\Bundle\BlogBundle\Service\CommentManager;

class ArticleTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateAnArticle()
    {
        $expectedArticle = $this->createArticle();

        $articleManager = new ArticleManager();
        $newArticle = $articleManager->create('New title', 'New article content');

        $this->assertEquals($expectedArticle, $newArticle);
    }

    public function testCommentAnArticle()
    {
        $expectedComment = $this->createComment();

        $commentManager = new CommentManager();
        $newComment = $commentManager->create('A new comment to an article');

        $this->assertEquals($expectedComment, $newComment);

        $articleManager = new ArticleManager();
        $articleManager->setCommentManager($commentManager);
        $article = $this->createArticle();
        $articleManager->addNewComment($article, 'A new comment to an article');

        $this->assertEquals(1, $article->getComments()->count());
        $this->assertEquals($expectedComment, $article->getComments()->get(0));
    }

    /**
     * Create a new article
     *
     * @return Article
     */
    private function createArticle()
    {
        return (new Article())
            ->setTitle('New title')
            ->setContent('New article content')
        ;
    }

    /**
     * Create a new comment
     *
     * @return Comment
     */
    private function createComment()
    {
        return (new Comment())
            ->setContent('A new comment to an article')
        ;
    }
}