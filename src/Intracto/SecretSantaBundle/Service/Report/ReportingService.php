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
        $dbal = $this->doctrine->getConnection();
        $year = $request->get('year');

        if ($year == "all" || $year == '') {
            $pools = $dbal->fetchAll('SELECT count(*) as poolCount FROM Pool');
        } else {
            $pools = $dbal->fetchAll('SELECT count(*) as poolCount FROM Pool WHERE year(sentdate) = ' . $year);
        }

        return $pools;
    }

    public function getEntries(Request $request)
    {
        $dbal = $this->doctrine->getConnection();
        $year = $request->get('year');

        if ($year == "all" || $year == '') {
            $entries = $dbal->fetchAll('SELECT count(*) as entryCount FROM Entry JOIN Pool on Pool.id = Entry.poolId');
        } else {
            $entries = $dbal->fetchAll('SELECT count(*) as entryCount FROM Entry JOIN Pool on Pool.id = Entry.poolId where year(Pool.sentdate) = ' . $year);
        }

        return $entries;
    }

    public function getFinishedWishlists(Request $request)
    {
        $dbal = $this->doctrine->getConnection();
        $year = $request->get('year');

        if ($year == "all" || $year == '') {
            $wishlists = $dbal->fetchAll('SELECT count(*) as wishListCount FROM Entry JOIN Pool on Pool.id = Entry.poolId WHERE wishlist_updated = TRUE');
        } else {
            $wishlists = $dbal->fetchAll('SELECT count(*) as wishListCount FROM Entry JOIN Pool on Pool.id = Entry.poolId where year(Pool.sentdate) = ' . $year . ' AND Entry.wishlist_updated = TRUE');
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