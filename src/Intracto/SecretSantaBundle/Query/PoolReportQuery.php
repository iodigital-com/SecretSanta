<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;

class PoolReportQuery
{
    /** @var Connection */
    private $dbal;
    /** @var FeaturedYearsQuery */
    private $featuredYearsQuery;

    /**
     * @param Connection $dbal
     * @param FeaturedYearsQuery $featuredYearsQuery
     */
    public function __construct(Connection $dbal, FeaturedYearsQuery $featuredYearsQuery)
    {
        $this->dbal = $dbal;
        $this->featuredYearsQuery = $featuredYearsQuery;
    }

    /**
     * @param Season $season
     * @return mixed
     */
    public function countPools(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS poolCount')
            ->from('Pool', 'p')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    public function compareSeasonsPools($year)
    {
        $season1 = new PoolYear($year);
        $season2 = new PoolYear($year - 1);

        $poolsSeason1 = $this->countPools($season1);

        if($season2) {
            $poolsSeason2 = $this->countPools($season2);
            $difference = $poolsSeason1[0]['poolCount'] - $poolsSeason2[0]['poolCount'];

            return $difference;
        }

        $difference = $poolsSeason1[0]['poolCount'];
        return $difference;
    }

    /**
     * @param \DateTime $date
     * @return mixed
     */
    public function countAllPoolsUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS poolCount')
            ->from('Pool', 'p')
            ->where('p.sentdate < :lastDay')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param Season $season
     * @return mixed
     */
    public function queryDataForMonthlyPoolChart(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS accumulatedPoolCountByMonth, p.sentdate AS month')
            ->from('Pool', 'p')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->groupBy('month(p.sentdate)')
            ->orderBy('month(p.sentdate) < 4, month(p.sentdate)')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @return array
     */
    public function queryDataForYearlyPoolChart()
    {
        $featuredYears = $this->featuredYearsQuery->getFeaturedYears();
        $poolChartData = [];

        foreach ($featuredYears['featured_years'] as $year) {
            $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-04-01')->format('Y-m-d H:i:s');

            $query = $this->dbal->createQueryBuilder()
                ->select('count(p.id) AS accumulatedPoolCountByYear')
                ->from('Pool', 'p')
                ->where('p.sentdate IS NOT NULL')
                ->andWhere('p.sentdate < :lastDay')
                ->setParameter('lastDay', $lastDay);

            $chartData = $query->execute()->fetchAll();

            $pool = [
                'year' => $year,
                'pool' => $chartData
            ];

            array_push($poolChartData, $pool);
        }

        return $poolChartData;
    }

    /**
     * @param \DateTime $date
     * @return mixed
     */
    public function queryDataForPoolChartUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS totalPoolCount, p.sentdate AS month')
            ->from('Pool', 'p')
            ->where('p.sentdate < :lastDay')
            ->groupBy('year(p.sentdate), month(p.sentdate)')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        $totalPoolChartData = $query->execute()->fetchAll();

        $accumulatedPoolCounter = 0;

        foreach ($totalPoolChartData as &$poolCount) {
            $accumulatedPoolCounter += $poolCount['totalPoolCount'];
            $poolCount['totalPoolCount'] = $accumulatedPoolCounter;
        }

        return $totalPoolChartData;
    }

    /**
     * @param PoolYear $season1
     * @param PoolYear $season2
     * @return mixed
     */
    public function calculatePoolCountDifferenceBetweenSeasons(PoolYear $season1, PoolYear $season2)
    {
        $poolCountSeason1 = $this->countPools($season1);
        $poolCountSeason2 = $this->countPools($season2);

        return $poolCountSeason1[0]['poolCount'] - $poolCountSeason2[0]['poolCount'];
    }
}