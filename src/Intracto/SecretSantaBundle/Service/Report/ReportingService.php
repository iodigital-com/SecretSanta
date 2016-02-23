<?php
namespace Intracto\SecretSantaBundle\Service\Report;
use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\HttpFoundation\Request;
class ReportingService
{
    private $cache;
    private $doctrine;
    public function __construct(CacheProvider $cache, $doctrine)
    {
        $this->cache = $cache;
        $this->doctrine = $doctrine;
    }
    public function getPools(Request $request)
    {
        /*if ($this->cache->contains('tracked_pools')) {
            return unserialize($this->cache->fetch('tracked_pools'));
        }*/
        $dbal = $this->doctrine->getConnection();
        $year = $request->get('year');
        if ($year == "all" || $year == '') {
            $pools = $dbal->fetchAll('SELECT * FROM Pool WHERE year(sentdate) = 2016');
        } else {
            $pools = $dbal->fetchAll('SELECT * FROM Pool WHERE year(sentdate) = ' . $year);
            //$this->cache->save('track_pools', serialize($pools), 3600);
        }
        return $pools;
    }
    public function getEntries(Request $request)
    {
        /*if ($this->cache->contains('tracked_entries')) {
            return unserialize($this->cache->fetch('tracked_entries'));
        }*/
        $dbal = $this->doctrine->getConnection();
        $year = $request->get('year');
        if ($year == "all" || $year == '') {
            $entries = $dbal->fetchAll('SELECT * FROM Entry JOIN Pool on Pool.id = Entry.poolId where year(Pool.sentdate) = 2016');
        } else {
            $entries = $dbal->fetchAll('SELECT * FROM Entry JOIN Pool on Pool.id = Entry.poolId where year(Pool.sentdate) = ' . $year);
            //$this->cache->save('tracked_entries', serialize($entries), 3600);
        }
        return $entries;
    }
    public function getFinishedWishlists(Request $request)
    {
        /*if ($this->cache->contains('tracked_wishlists')) {
            return unserialize($this->cache->fetch('tracked_wishlists'));
        }*/
        $dbal = $this->doctrine->getConnection();
        $year = $request->get('year');
        if ($year == "all" || $year == '') {
            $wishlists = $dbal->fetchAll('SELECT * FROM Entry JOIN Pool on Pool.id = Entry.poolId WHERE year(Pool.sentdate) = 2016 AND wishlist_updated = TRUE');
        } else {
            $wishlists = $dbal->fetchAll('SELECT * FROM Entry JOIN Pool on Pool.id = Entry.poolId where year(Pool.sentdate) = ' . $year . ' AND Entry.wishlist_updated = TRUE');
            //$this->cache->save('tracked_wishlists', serialize($wishlists), 3600);
        }
        return $wishlists;
    }

    public function getYears()
    {
        $dbal = $this->doctrine->getConnection();
        $featured_years = $dbal->fetchAll('SELECT DISTINCT year(sentdate) as featured_year FROM Pool WHERE year(sentdate) IS NOT NULL ORDER BY year(sentdate) DESC');

        return $featured_years;
    }
}