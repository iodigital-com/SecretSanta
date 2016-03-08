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

        if ($ipv4[0]['ipv4Count'] + $ipv6[0]['ipv6Count'] != 0) {
            $ipv4Percentage = $ipv4[0]['ipv4Count'] / ($ipv4[0]['ipv4Count'] + $ipv6[0]['ipv6Count']);
            $ipv6Percentage = $ipv6[0]['ipv6Count'] / ($ipv4[0]['ipv4Count'] + $ipv6[0]['ipv6Count']);

            return [
                'ipv4_percentage' => $ipv4Percentage,
                'ipv6_percentage' => $ipv6Percentage,
            ];
        }

        return [];
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

        return $query->execute()->fetchAll();
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

        return $query->execute()->fetchAll();
    }
}