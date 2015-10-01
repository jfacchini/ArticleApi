<?php

namespace Jfacchini\Bundle\BlogBundle\Service;

use Exception;
use Jfacchini\Bundle\BlogBundle\Entity\Rate;

class RateManager
{
    public function create($value)
    {
        if (!is_integer($value)) {
            throw new Exception('A rate must be an integer');
        }

        if ($value < 0 || $value > 5) {
            throw new Exception(sprintf('Rate range is [0 - 5] but "%s" given', $value));
        }

        return (new Rate())->setValue($value);
    }
}