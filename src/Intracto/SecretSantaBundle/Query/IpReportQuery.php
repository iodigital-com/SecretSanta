<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;
use Proxies\__CG__\Intracto\SecretSantaBundle\Entity\Pool;

class IpReportQuery
{
    /** @var Connection */
    private $dbal;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal)
    {
        $this->dbal = $dbal;
    }

    /**
     * @param Season $season
     * @return array
     */
    public function calculateIpUsage(Season $season)
    {
        $ipv4 = $this->queryIpv4Records($season);
        $ipv6 = $this->queryIpv6Records($season);

        return [
            'ipv4' => $ipv4,
            'ipv6' => $ipv6,
        ];
    }

    /**
     * @param Season $season
     * @return mixed
     */
    private function queryIpv4Records(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(e.ipv4) AS ipv4Count')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('e.ipv4 IS NOT NULL')
            ->andWhere('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetch();
    }

    /**
     * @param Season $season
     * @return mixed
     */
    private function queryIpv6Records(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(e.ipv6) AS ipv6Count')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('e.ipv6 IS NOT NULL')
            ->andWhere('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetch();
    }
}