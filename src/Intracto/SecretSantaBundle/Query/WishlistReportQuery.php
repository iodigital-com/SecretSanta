<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;

class WishlistReportQuery
{
    private $dbal;
    private $entryReportQueries;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal, $entryReportQueries)
    {
        $this->dbal = $dbal;
        $this->entryReportQueries = $entryReportQueries;
    }

    /**
     * @param Period|null $period
     * @return float
     */
    public function calculateCompletedWishlists(Period $period = null)
    {
        if ($period!= null) {
            $wishlists = $this->countWishlists($period);
            $entries = $this->entryReportQueries->countEntries($period);

            if ($entries[0]['entryCount'] != 0) {
                return (implode($wishlists[0]) / implode($entries[0])) * 100;
            }

            throw new NoResultException();
        }

        $wishlists = $this->countWishlists();
        $entries = $this->entryReportQueries->countEntries();

        if ($entries[0]['entryCount'] != 0) {
            return (implode($wishlists[0]) / implode($entries[0])) * 100;
        }

        throw new NoResultException();
    }

    /**
     * @param Period|null $period
     * @return array
     */
    private function countWishlists(Period $period = null)
    {
        if($period != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) as wishlistCount
                FROM Pool p
                JOIN Entry e on p.id = e.poolId
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay AND e.wishlist_updated = TRUE',
                ['firstDay' => $period->getStart(), 'lastDay' => $period->getEnd()]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(*) as wishlistCount
            FROM Entry
            JOIN Pool on Pool.id = Entry.poolId
            WHERE wishlist_updated = TRUE'
        );
    }

    /**
     * @param Period|null $period
     * @return float
     */
    public function calculateCompletedWishlistsUntilDate(Period $period = null)
    {
        $totalWishlists = $this->countAllWishlistsUntilDate($period);
        $totalEntries = $this->entryReportQueries->countAllEntriesUntilDate($period);

        if ($totalEntries[0]['totalEntryCount'] != 0) {
            return (implode($totalWishlists[0]) / implode($totalEntries[0])) * 100;
        }

        throw new NoResultException();
    }

    /**
     * @param Period|null $period
     * @return array
     */
    private function countAllWishlistsUntilDate(Period $period = null)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as totalWishlistCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay AND e.wishlist_updated = TRUE',
            ['lastDay' => $period->getEnd()]
        );
    }
}