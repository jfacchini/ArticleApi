<?php

namespace Jfacchini\Bundle\BlogBundle\Tests;

use Exception;
use Jfacchini\Bundle\BlogBundle\Entity\Article;
use Jfacchini\Bundle\BlogBundle\Entity\Comment;
use Jfacchini\Bundle\BlogBundle\Entity\Rate;
use Jfacchini\Bundle\BlogBundle\Service\ArticleManager;
use Jfacchini\Bundle\BlogBundle\Service\CommentManager;
use Jfacchini\Bundle\BlogBundle\Service\RateManager;

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

    public function testRateAnArticle()
    {
        $expectedRate = (new Rate())->setValue(5);

        $rateManager = new RateManager();
        try {
            $rate = $rateManager->create('a');
            $this->assertFalse(true);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }

        $rate = $rateManager->create(5);
        $this->assertEquals($expectedRate, $rate);

        $articleManager = new ArticleManager();
        $articleManager->setRateManager($rateManager);

        $article = $this->createArticle();
        $this->assertNull($articleManager->getRatesAverage($article));

        $articleManager->addNewRate($article, 5);
        $this->assertEquals(1, $article->getRates()->count());
        $this->assertEquals($expectedRate, $article->getRates()->get(0));

        $articleManager->addNewRate($article, 4);
        $this->assertEquals(4.5, $articleManager->getRatesAverage($article));
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