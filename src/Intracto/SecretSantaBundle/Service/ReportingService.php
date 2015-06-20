<?php

namespace Intracto\SecretSantaBundle\Service;

use Doctrine\Common\Cache\CacheProvider;

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
            SELECT count(*) created, date(sentdate) sentdate
            FROM Pool
            GROUP BY date(sentdate)
            ORDER BY date(sentdate)
        ');

        $this->cache->save('report_pools', serialize($pools), 3600);

        return $pools;
    }

    public function getGaCountries()
    {
        if ($this->cache->contains('ga_countries')) {
            return unserialize($this->cache->fetch('ga_countries'));
        }

        require __DIR__.'/../../../../lib/gapi.class.php';

        $ga = new \gapi(
            $this->ga_email,
            $this->ga_password
        );

        $ga->requestReportData(
            $this->ga_profile_id,
            array('country'),
            array('visits'),
            '-visits',
            null,
            date('Y-m-d', strtotime('-1 month')),
            date('Y-m-d'),
            1,
            100
        );

        $results = array(
            'totals' => $ga->getVisits(),
        );

        foreach ($ga->getResults() as $country) {
            $results['countries'][(string) $country] = $country->getVisits();
        }

        $this->cache->save('ga_countries', serialize($results), 3600);

        return $results;
    }
}