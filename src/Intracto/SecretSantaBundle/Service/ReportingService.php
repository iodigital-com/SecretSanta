<?php

namespace Intracto\SecretSantaBundle\Service;

use Doctrine\Common\Cache\CacheProvider;
use Intracto\SecretSantaBundle\Model\AnalyticsOptions;
use Intracto\SecretSantaBundle\Service\Analytics\GApi;

class ReportingService
{
    private $cache;
    private $doctrine;
    private $ga_email;
    private $ga_password;
    private $ga_profile_id;

    public function __construct(CacheProvider $cache, $doctrine, $ga_email, $ga_password, $ga_profile_id)
    {
        $this->cache = $cache;
        $this->doctrine = $doctrine;
        $this->ga_email = $ga_email;
        $this->ga_password = $ga_password;
        $this->ga_profile_id = $ga_profile_id;
    }

    public function getPools()
    {
        if ($this->cache->contains('report_pools')) {
            return unserialize($this->cache->fetch('report_pools'));
        }

        $dbal = $this->doctrine->getConnection();

        $pools = $dbal->fetchAll('
            SELECT count(*) created, ifnull(date(sentdate), now()) sentdate
            FROM Pool
            GROUP BY date(sentdate)
            ORDER BY date(sentdate)
        ');

        $this->cache->save('report_pools', serialize($pools), 3600);

        return $pools;
    }

    public function getAnalytics(AnalyticsOptions $options)
    {
        $cachingId = $options->uniqueCachingString();
        if ($this->cache->contains($cachingId)) {
            return unserialize($this->cache->fetch($cachingId));
        }

        $ga = new GApi(
            $this->ga_email,
            $this->ga_password
        );

        $ga->requestReportData(
            $this->ga_profile_id,
            $options->getDimensions(),
            $options->getMetrics(),
            $options->getSortMetric(),
            $options->getFilter(),
            $options->getStartDate(),
            $options->getEndDate(),
            $options->getStartIndex(),
            $options->getMaxResults()
        );

        $results = array(
            'totals' => $ga->getVisits(),
        );

        foreach ($ga->getResults() as $item) {
            $results[$options->getDimension()][(string) $item] = $item->getVisits();
        }

        $this->cache->save($cachingId, serialize($results), 3600);

        return $results;
    }
}
