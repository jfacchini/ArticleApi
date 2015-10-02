<?php

namespace Jfacchini\Bundle\BlogBundle\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    /**
     * Retrieve an article with all its comments
     *
     * @param int $id Article id which needs to be retrieved
     * @return \Doctrine\ORM\Query
     */
    public function findByIdWithAllCommentsQuery($id)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->select('a, c') // Perform just one request
            ->innerJoin('a.comments', 'c')
            ->where(
                $qb->expr()->eq('a.id', ':id')
            )
            ->setParameter('id', $id)
        ;

        return $qb->getQuery();
    }

    /**
     * Retrieve an article with its comments for the last 24 hours
     *
     * @param $id
     * @return \Doctrine\ORM\Query
     */
    public function findArticleByIdWithCommentsFor24HoursQuery($id)
    {
        $qb = $this->createQueryBuilder('a');

        $dateTimeBefore24Hours = (new DateTime())->modify('- 24 hour');

        $qb
            ->select('a, c')
            ->innerJoin('a.comments', 'c')
            ->where(
                $qb->expr()->eq('a.id', ':id')
            )
            ->andWhere(
                $qb->expr()->gt('c.createdDate', ':date')
            )
            ->setParameter('id', $id)
            ->setParameter('date', $dateTimeBefore24Hours)
        ;

        return $qb->getQuery();
    }
}