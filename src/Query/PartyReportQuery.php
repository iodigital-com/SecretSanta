<?php

namespace App\Query;

use Doctrine\DBAL\Connection;

class PartyReportQuery
{
    private Connection $dbal;
    private FeaturedYearsQuery $featuredYearsQuery;

    public function __construct(Connection $dbal, FeaturedYearsQuery $featuredYearsQuery)
    {
        $this->dbal = $dbal;
        $this->featuredYearsQuery = $featuredYearsQuery;
    }

    public function countParties(Season $season): int
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS partyCount')
            ->from('party', 'p')
            ->where('p.sent_date >= :firstDay')
            ->andWhere('p.sent_date < :lastDay')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        $partyCount = $query->execute()->fetchAll();

        return (int) $partyCount[0]['partyCount'];
    }

    /**
     * @return mixed
     */
    public function countAllPartiesUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS partyCount')
            ->from('party', 'p')
            ->where('p.sent_date < :lastDay')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @return mixed
     */
    public function queryDataForMonthlyPartyChart(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS accumulatedPartyCountByMonth, p.sent_date AS month')
            ->from('party', 'p')
            ->where('p.sent_date >= :firstDay')
            ->andWhere('p.sent_date < :lastDay')
            ->groupBy('month(p.sent_date)')
            ->orderBy('month(p.sent_date) < 4, month(p.sent_date)')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    public function queryDataForYearlyPartyChart(): array
    {
        $featuredYears = $this->featuredYearsQuery->getFeaturedYears();
        $partyChartData = [];

        foreach ($featuredYears['featured_years'] as $year) {
            $firstDay = \DateTime::createFromFormat('Y-m-d', $year.'-04-01')->format('Y-m-d H:i:s');
            $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 .'-04-01')->format('Y-m-d H:i:s');

            $query = $this->dbal->createQueryBuilder()
                ->select('count(p.id) AS accumulatedPartyCountByYear')
                ->from('party', 'p')
                ->where('p.sent_date IS NOT NULL')
                ->andWhere('p.sent_date >= :firstDay')
                ->andWhere('p.sent_date < :lastDay')
                ->setParameter('firstDay', $firstDay)
                ->setParameter('lastDay', $lastDay);

            $chartData = $query->execute()->fetchAll();

            $party = [
                'year' => $year,
                'party' => $chartData,
            ];

            $partyChartData[] = $party;
        }

        return $partyChartData;
    }

    /**
     * @return mixed
     */
    public function queryDataForPartyChartUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS totalPartyCount, p.sent_date AS month')
            ->from('party', 'p')
            ->where('p.sent_date < :lastDay')
            ->groupBy('year(p.sent_date), month(p.sent_date)')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        $totalPartyChartData = $query->execute()->fetchAll();

        $accumulatedPartyCounter = 0;

        foreach ($totalPartyChartData as &$partyCount) {
            $accumulatedPartyCounter += $partyCount['totalPartyCount'];
            $partyCount['totalPartyCount'] = $accumulatedPartyCounter;
        }

        return $totalPartyChartData;
    }

    /**
     * @return mixed
     */
    public function calculatePartyCountDifferenceBetweenSeasons(Season $season1, Season $season2)
    {
        $partyCountSeason1 = $this->countParties($season1);

        try {
            $partyCountSeason2 = $this->countParties($season2);
        } catch (\Exception) {
            return $partyCountSeason1;
        }

        return $partyCountSeason1 - $partyCountSeason2;
    }
}
