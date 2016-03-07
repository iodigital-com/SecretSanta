<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;
use Proxies\__CG__\Intracto\SecretSantaBundle\Entity\Pool;

class IpReportQuery
{
    /**
     * @var Connection
     */
    private $dbal;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal)
    {
        $this->dbal = $dbal;
    }

    /**
     * @param PoolYear $poolYear
     * @return array
     */
    public function calculateIpUsage(PoolYear $poolYear)
    {
        $ipv4 = $this->queryIpv4Records($poolYear);
        $ipv6 = $this->queryIpv6Records($poolYear);

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
     * @param PoolYear $poolYear
     * @return mixed
     */
    private function queryIpv4Records(PoolYear $poolYear)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(e.ipv4) AS ipv4Count')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('e.ipv4 IS NOT NULL')
            ->andWhere('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->setParameter('firstDay', $poolYear->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $poolYear->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param PoolYear $poolYear
     * @return mixed
     */
    private function queryIpv6Records(PoolYear $poolYear)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(e.ipv6) AS ipv6Count')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('e.ipv6 IS NOT NULL')
            ->andWhere('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->setParameter('firstDay', $poolYear->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $poolYear->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }
}