<?php

namespace Jfacchini\Bundle\BlogBundle\Tests;

use Jfacchini\Bundle\BlogBundle\Entity\Article;
use Jfacchini\Bundle\BlogBundle\Service\ArticleManager;

class ArticleTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateAnArticle()
    {
        $expectedArticle = new Article();
        $expectedArticle->setTitle('New title');
        $expectedArticle->setContent('New article content');

        $articleManager = new ArticleManager();
        $newArticle = $articleManager->create('New title', 'New article content');

        $this->assertEquals($expectedArticle, $newArticle);
    }
}