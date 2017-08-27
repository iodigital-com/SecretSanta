<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Driver\Connection;

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
     *
     * @return array
     */
    public function calculateIpUsage(Season $season)
    {
        $ipv4 = $this->queryIpv4Records($season);
        $ipv6 = $this->queryIpv6Records($season);

        if ($ipv4['ipv4Count'] == 0 && $ipv6['ipv6Count'] == 0) {
            return [];
        }

        return [
            'ipv4' => $ipv4,
            'ipv6' => $ipv6,
        ];
    }

    /**
     * @param Season $season
     *
     * @return mixed
     */
    private function queryIpv4Records(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(e.ipv4) AS ipv4Count')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('e.ipv4 IS NOT NULL')
            ->andWhere('p.sent_date >= :firstDay')
            ->andWhere('p.sent_date < :lastDay')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetch();
    }

    /**
     * @param Season $season
     *
     * @return mixed
     */
    private function queryIpv6Records(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(e.ipv6) AS ipv6Count')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('e.ipv6 IS NOT NULL')
            ->andWhere('p.sent_date >= :firstDay')
            ->andWhere('p.sent_date < :lastDay')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetch();
    }
}
