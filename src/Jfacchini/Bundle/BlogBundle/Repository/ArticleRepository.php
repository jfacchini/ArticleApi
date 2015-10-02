<?php

namespace Jfacchini\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    /**
     * Retrieve an article with all its comments
     *
     * @param int $id Article id which needs to be retrieved
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
}