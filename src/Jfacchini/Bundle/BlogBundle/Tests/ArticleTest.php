<?php

namespace Jfacchini\Bundle\BlogBundle\Tests;

use Jfacchini\Bundle\BlogBundle\Entity\Article;
use Jfacchini\Bundle\BlogBundle\Entity\Comment;
use Jfacchini\Bundle\BlogBundle\Entity\Rate;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ArticleTest extends KernelTestCase
{
    /** @var ContainerInterface */
    private static $container;

    public static function setUpBeforeClass()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        self::$container = $kernel->getContainer();
    }

    public function testCreateAnArticle()
    {
        $em = self::$container->get('doctrine.orm.default_entity_manager');
        $validator = self::$container->get('validator');
        $articleManager = self::$container->get('blog_article.article_manager');

        // Test validation : title and content should not be null
        $article = new Article();
        $errors = $validator->validate($article);
        $this->assertCount(2, $errors);

        $article = $this->createArticle();
        $errors = $validator->validate($article);
        $this->assertCount(0, $errors);

        // Test that an article has a created date and has been persisted
        $articleManager->create($article);
        $this->assertNotNull($article->getCreatedDate());
        //TODO: test if the created date is today.
        $this->assertEquals(\Doctrine\ORM\UnitOfWork::STATE_MANAGED, $em->getUnitOfWork()->getEntityState($article));
    }

    public function testCommentAnArticle()
    {
        $em = self::$container->get('doctrine.orm.default_entity_manager');
        $validator = self::$container->get('validator');
        $articleManager = self::$container->get('blog_article.article_manager');
        $commentManager = self::$container->get('blog_article.comment_manager');

        // Test validation : content should not be null
        $comment = new Comment();
        $errors = $validator->validate($comment);
        $this->assertCount(1, $errors);

        $comment = $this->createComment();
        $errors = $validator->validate($comment);
        $this->assertCount(0, $errors);

        $commentManager->create($comment);
        $this->assertNotNull($comment->getCreatedDate());
        $this->assertEquals(\Doctrine\ORM\UnitOfWork::STATE_MANAGED, $em->getUnitOfWork()->getEntityState($comment));

        $comment = $this->createComment();
        $article = $this->createArticle();
        $articleManager->addNewComment($article, $comment);

        $this->assertEquals(1, $article->getComments()->count());
        $this->assertEquals(\Doctrine\ORM\UnitOfWork::STATE_MANAGED, $em->getUnitOfWork()->getEntityState($comment));
    }

    public function testRateAnArticle()
    {
        $em = self::$container->get('doctrine.orm.default_entity_manager');
        $validator = self::$container->get('validator');
        $articleManager = self::$container->get('blog_article.article_manager');
        $rateManager = self::$container->get('blog_article.rate_manager');

        // Test validation : value shoult not be null
        $rate = new Rate();
        $errors = $validator->validate($rate);
        $this->assertCount(1, $errors);
        // The value type must be an integer
        $rate->setValue('a');
        $errors = $validator->validate($rate);
        $this->assertCount(1, $errors);
        $rate->setValue(4.2);
        $errors = $validator->validate($rate);
        $this->assertCount(1, $errors);
        // The value range must be [0 - 5]
        $rate->setValue(6);
        $errors = $validator->validate($rate);
        $this->assertCount(1, $errors);
        $rate->setValue(-1);
        $errors = $validator->validate($rate);
        $this->assertCount(1, $errors);

        $rate = $this->createRate();
        $errors = $validator->validate($rate);
        $this->assertCount(0, $errors);

        $rateManager->create($rate);
        $this->assertEquals(\Doctrine\ORM\UnitOfWork::STATE_MANAGED, $em->getUnitOfWork()->getEntityState($rate));

        $article = $this->createArticle();
        $this->assertNull($articleManager->getRatesAverage($article));

        $rate = $this->createRate();
        $articleManager->addNewRate($article, $rate);
        $this->assertEquals(1, $article->getRates()->count());
        $this->assertEquals(\Doctrine\ORM\UnitOfWork::STATE_MANAGED, $em->getUnitOfWork()->getEntityState($rate));

        $rate = $this->createRate()->setValue(4);
        $articleManager->addNewRate($article, $rate);
        $this->assertEquals(4.5, $articleManager->getRatesAverage($article));
    }

    public function testRetrieveArticle()
    {
        $em = self::$container->get('doctrine.orm.default_entity_manager');
        $repository = $em->getRepository('BlogBundle:Article');

        $sql = 'SELECT a0_.id AS id_0, a0_.title AS title_1, a0_.content AS content_2, a0_.created_date AS created_date_3, c1_.id AS id_4, c1_.content AS content_5, c1_.article_id AS article_id_6 FROM article a0_ INNER JOIN comment c1_ ON a0_.id = c1_.article_id WHERE a0_.id = ?';
        $this->assertEquals($sql, $repository->findByIdWithAllCommentsQuery(1)->getSQL());

        //TODO: Mock the repository to retrieve an article with comments
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

    /**
     * Create a new rate
     *
     * @return Rate
     */
    private function createRate()
    {
        return (new Rate())
            ->setValue(5)
        ;
    }
}