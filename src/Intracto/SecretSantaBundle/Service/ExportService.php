<?php

namespace Intracto\SecretSantaBundle\Service;

use Intracto\SecretSantaBundle\Query\ParticipantReportQuery;
use Intracto\SecretSantaBundle\Query\Season;
use Symfony\Component\Routing\RouterInterface;

class ExportService
{
    /**
     * @var ParticipantReportQuery
     */
    private $participantReportQuery;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param ParticipantReportQuery $participantReportQuery
     * @param RouterInterface        $router
     */
    public function __construct(
        ParticipantReportQuery $participantReportQuery,
        RouterInterface $router
    ) {
        $this->participantReportQuery = $participantReportQuery;
        $this->router = $router;
    }

    /**
     * @param Season $season
     * @param bool   $isAdmin
     */
    public function export(Season $season, bool $isAdmin = false)
    {
        $result = $this->participantReportQuery->fetchMailsForExport($season, $isAdmin);

        $reusePartyBaseUrl = $this->getPartyReuseBaseUrl();
        $handle = fopen($this->getOutputLocation($isAdmin), 'w+');

        foreach ($result as $row) {
            $export = [
                $row['name'],
                $row['email'],
                $row['party_id'],
                $row['url'],
                $row['locale'],
            ];

            if ($isAdmin) {
                $export[] = $reusePartyBaseUrl.$row['list_url'];
            }

            fputcsv(
                $handle,
                $export,
                ','
            );
        }

        fclose($handle);
    }

    /**
     * @param bool $isAdmin
     *
     * @return string
     */
    private function getOutputLocation(bool $isAdmin): string
    {
        if ($isAdmin) {
            return '/tmp/'.date('Y-m-d-H.i.s').'_admins.csv';
        }

        return '/tmp/'.date('Y-m-d-H.i.s').'_participants.csv';
    }

    /**
     * @return string
     */
    private function getPartyReuseBaseUrl(): string
    {
        $url = $this->router->generate(
            'party_reuse',
            ['listurl' => '1'],
            true
        );

        // URL was generated for party 1, strip the 1 to get the base URL
        return substr($url, 0, -1);
    }
}
