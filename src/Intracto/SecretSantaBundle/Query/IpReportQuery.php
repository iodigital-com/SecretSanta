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
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    public function calculateIpUsage($firstDay = null, $lastDay = null)
    {
        if($firstDay != null && $lastDay != null) {
            $ipv4 = $this->queryIpv4Records($firstDay, $lastDay);
            $ipv6 = $this->queryIpv6Records($firstDay, $lastDay);

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
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function queryIpv4Records($firstDay = null, $lastDay = null)
    {
        if($firstDay != null && $lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(e.ipv4) as ipv4Count
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE e.ipv4 IS NOT NULL AND p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(ipv4) as ipv4Count
            FROM Entry
            WHERE ipv4 IS NOT NULL'
        );
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function queryIpv6Records($firstDay = null, $lastDay = null)
    {
        if($firstDay != null && $lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(e.ipv6) as ipv6Count
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE ipv6 IS NOT NULL AND p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(ipv6) as ipv6Count
            FROM Entry
            WHERE ipv6 IS NOT NULL'
        );
    }

}