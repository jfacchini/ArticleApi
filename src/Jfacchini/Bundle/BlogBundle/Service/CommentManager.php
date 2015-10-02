<?php

namespace Jfacchini\Bundle\BlogBundle\Service;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Jfacchini\Bundle\BlogBundle\Entity\Comment;

/**
 * Class CommentManager
 * @package Jfacchini\Bundle\BlogBundle\Service
 *
 * Manage comment entity
 */
class CommentManager
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Create a new Comment, persist it
     *
     * @param Comment $comment
     */
    public function create(Comment $comment)
    {
        $comment->setCreatedDate(new DateTime());
        $this->em->persist($comment);
    }
}