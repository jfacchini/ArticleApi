<?php

namespace Jfacchini\Bundle\BlogBundle\Service;

use Jfacchini\Bundle\BlogBundle\Entity\Article;

class ArticleManager
{
    /**
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
}