<?php

namespace App\Query;

use Google_Client;
use Google_Service_Analytics;

class GoogleAnalyticsQuery
{
    private $viewId;
    private $clientSecret;

    public function __construct($viewId, $clientSecret)
    {
        $this->viewId = $viewId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @throws \Google_Exception
     */
    public function getAnalyticsReport(?int $year = null): array
    {
        $season = new Season($year);

        $client = new Google_Client();
        putenv(sprintf('GOOGLE_APPLICATION_CREDENTIALS=%s', $this->clientSecret));
        $client->setAuthConfig($this->clientSecret);
        $client->setScopes([Google_Service_Analytics::ANALYTICS]);

        $analytics = new Google_Service_Analytics($client);
        $analyticsViewId = $this->viewId;

        $startDate = $season->getStart()->format('Y-m-d');
        $endDate = $season->getEnd()->format('Y-m-d');
        $metrics = 'ga:sessions';
        $gaParameters = new GaParameters($analytics, $analyticsViewId, $startDate, $endDate, $metrics);

        return [
            'countries' => $this->getTopCountries($gaParameters)->rows,
            'language' => $this->getTopLanguages($gaParameters),
            'deviceCategory' => $this->getDeviceCategory($gaParameters)->rows,
            'browser' => $this->getBrowsers($gaParameters)->rows,
        ];
    }

    /**
     * @return mixed
     */
    public function getTopCountries(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:country',
                'sort' => '-ga:sessions, -ga:country',
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getTopLanguages(GaParameters $gaParameters)
    {
        $query = $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:language',
                'sort' => '-ga:sessions, -ga:language',
            ]
        );

        // Fetch list of known languages
        $availableLanguages = Languages::$list;

        // Init top languages for return
        $topLanguages = [];

        foreach ($query->rows as $row) {
            // Get language code by filtering first item from locale. Example en_EN, en-en, en_en become en
            $languageCode = strtok(strtok($row[0], '-'), '_');

            // Check if language code is in avaiblable list
            if (!array_key_exists($languageCode, $availableLanguages)) {
                continue;
            }

            // Get language name via available list
            $languageName = $availableLanguages[$languageCode];

            // Init language in topLanguage list if it's not there yet
            if (!isset($topLanguages[$languageName])) {
                $topLanguages[$languageName] = [ucfirst($languageName), 0];
            }

            // Add numbers
            $topLanguages[$languageName][1] += $row[1];
        }

        // Reset keys and return
        return array_values($topLanguages);
    }

    /**
     * @return mixed
     */
    public function getDeviceCategory(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:deviceCategory',
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getBrowsers(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:browser',
                'sort' => '-ga:sessions, -ga:browser',
                'max-results' => 5,
            ]
        );
    }
}
