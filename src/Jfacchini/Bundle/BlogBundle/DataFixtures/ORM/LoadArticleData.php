<?php

namespace Jfacchini\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Jfacchini\Bundle\BlogBundle\Entity\Article;
use Jfacchini\Bundle\BlogBundle\Entity\Comment;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadArticleData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        $article = (new Article())
            ->setTitle('Title test 1')
            ->setContent('Some content for the article 1')
            ->setCreatedDate((new \DateTime())->modify('- 2 day'))
            ->setAuthorEmail('test@domain.tld')
        ;
        $em->persist($article);
        $em->flush($article);

        $comments = [
            [
                'content' => 'Nice comment',
                'created_date' => (new \DateTime())->modify('- 30 hour'),
            ],
            [
                'content' => 'Bad comment',
                'created_date' => (new \DateTime())->modify('- 12 hour'),
            ],
        ];

        foreach ($comments as $commentArray) {
            $comment = (new Comment())
                ->setContent($commentArray['content'])
                ->setCreatedDate($commentArray['created_date'])
                ->setArticle($article)
            ;
            $em->persist($comment);
        }
        $em->flush();
    }
}