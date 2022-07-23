<?php

namespace App\Query;

use Doctrine\DBAL\Connection;

class WishlistReportQuery
{
    private Connection $dbal;
    private ParticipantReportQuery $participantReportQuery;

    public function __construct(Connection $dbal, ParticipantReportQuery $participantReportQuery)
    {
        $this->dbal = $dbal;
        $this->participantReportQuery = $participantReportQuery;
    }

    public function calculateCompletedWishlists(Season $season): float
    {
        $wishlistCount = $this->countWishlists($season);
        $participantCount = $this->participantReportQuery->countParticipants($season);

        if ($participantCount === 0) {
            throw new NoResultException();
        }

        return (implode($wishlistCount[0]) / $participantCount) * 100;
    }

    /**
     * @return mixed
     */
    private function countWishlists(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS wishlistCount')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.sent_date >= :firstDay')
            ->andWhere('p.sent_date < :lastDay')
            ->andWhere('wishlist_updated_time IS NOT NULL')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    public function calculateCompletedWishlistsUntilDate(\DateTime $date): float
    {
        $totalWishlists = $this->countAllWishlistsUntilDate($date);
        $totalParticipants = $this->participantReportQuery->countAllParticipantsUntilDate($date);

        if ($totalParticipants[0]['totalParticipantCount'] != 0) {
            return (implode($totalWishlists[0]) / implode($totalParticipants[0])) * 100;
        }

        throw new NoResultException();
    }

    /**
     * @return mixed
     */
    private function countAllWishlistsUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS totalWishlistCount')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.sent_date < :lastDay')
            ->andWhere('wishlist_updated_time IS NOT NULL')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    public function calculateCompletedWishlistDifferenceBetweenSeasons(Season $season1, Season $season2): float
    {
        $completedWishlistsSeason1 = $this->calculateCompletedWishlists($season1);

        try {
            $completedWishlistsSeason2 = $this->calculateCompletedWishlists($season2);
        } catch (\Exception $e) {
            return $completedWishlistsSeason1;
        }

        return $completedWishlistsSeason1 - $completedWishlistsSeason2;
    }
}
