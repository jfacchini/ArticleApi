<?php

namespace Jfacchini\Bundle\BlogBundle\Service;

use Jfacchini\Bundle\BlogBundle\Entity\Comment;

/**
 * Class CommentManager
 * @package Jfacchini\Bundle\BlogBundle\Service
 *
 * Manage comment entity
 */
class CommentManager
{
    /**
     * Create a new Comment entity with a given content
     *
     * @param string $content Content of the comment
     * @return Comment
     */
    public function create($content)
    {
        return (new Comment())
            ->setContent($content)
        ;
    }
}