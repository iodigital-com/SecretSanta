<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;

class IpReportQuery
{
    private $dbal;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal)
    {
        $this->dbal = $dbal;
    }

    /**
     * @param Period|null $period
     * @return array
     */
    public function calculateIpUsage(Period $period = null)
    {
        if($period != null) {
            $ipv4 = $this->queryIpv4Records($period);
            $ipv6 = $this->queryIpv6Records($period);

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

        $ipv4 = $this->queryIpv4Records();
        $ipv6 = $this->queryIpv6Records();

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
     * @param Period|null $period
     * @return array
     */
    private function queryIpv4Records(Period $period = null)
    {
        if($period != null) {
            return $this->dbal->fetchAll(
                'SELECT count(e.ipv4) as ipv4Count
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE e.ipv4 IS NOT NULL AND p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $period->getStart(), 'lastDay' => $period->getEnd()]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(ipv4) as ipv4Count
            FROM Entry
            WHERE ipv4 IS NOT NULL'
        );
    }

    /**
     * @param Period|null $period
     * @return array
     */
    private function queryIpv6Records(Period $period = null)
    {
        if($period != null) {
            return $this->dbal->fetchAll(
                'SELECT count(e.ipv6) as ipv6Count
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE ipv6 IS NOT NULL AND p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $period->getStart(), 'lastDay' => $period->getEnd()]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(ipv6) as ipv6Count
            FROM Entry
            WHERE ipv6 IS NOT NULL'
        );
    }

}