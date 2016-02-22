<?php

namespace Intracto\SecretSantaBundle\Service\Report;

use Doctrine\Common\Cache\CacheProvider;


class ReportingService
{
    private $cache;
    private $doctrine;

    public function __construct(CacheProvider $cache, $doctrine){
        $this->cache = $cache;
        $this->doctrine = $doctrine;
    }

    public function getPools(){
        if($this->cache->contains('tracked_pools')){
            return unserialize($this->cache->fetch('tracked_pools'));
        }

        $dbal = $this->doctrine->getConnection();

        $pools = $dbal->fetchAll('SELECT * FROM Pool ORDER BY sentdate');

        $this->cache->save('track_pools', serialize($pools), 3600);

        return $pools;
    }
}