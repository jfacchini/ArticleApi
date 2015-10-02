<?php

namespace Jfacchini\Bundle\BlogBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Jfacchini\Bundle\BlogBundle\Entity\Rate;

class RateManager
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Create a new rate, persist it
     *
     * @param Rate $rate
     */
    public function create(Rate $rate)
    {
        $this->em->persist($rate);
    }
}