<?php

namespace Intracto\SecretSantaBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Intracto\SecretSantaBundle\Entity\Pool;

class PoolEvent extends Event
{
    private $pool;

    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    public function getPool()
    {
        return $this->pool;
    }
}
