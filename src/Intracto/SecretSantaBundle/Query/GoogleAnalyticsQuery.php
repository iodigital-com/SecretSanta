<?php

namespace Intracto\SecretSantaBundle\Query;

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
     * @param null $year
     * @return array
     * @throws \Google_Exception
     */
    public function getAnalyticsReport($year = null)
    {
        $season = new Season($year);

        $client = new Google_Client();
        $credentials = $client->loadServiceAccountJson($this->clientSecret, "https://www.googleapis.com/auth/analytics.readonly");
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        $analytics = new Google_Service_Analytics($client);
        $analyticsViewId = $this->viewId;
        $startDate = $season->getStart()->format('Y-m-d');
        $endDate = $season->getEnd()->format('Y-m-d');
        $metrics = 'ga:sessions';

        $gaParameters = new GaParameters($analytics, $analyticsViewId, $startDate, $endDate, $metrics);

        return [
            'countries' => $this->getTopCountries($gaParameters)->rows,
            'language' => $this->getTopLanguages($gaParameters)->rows,
            'deviceCategory' => $this->getDeviceCategory($gaParameters)->rows,
            'browser' => $this->getBrowsers($gaParameters)->rows,
        ];
    }

    /**
     * @param GaParameters $gaParameters
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
                'sort' => '-ga:sessions, -ga:country'
            ]
        );
    }

    /**
     * @param GaParameters $gaParameters
     * @return mixed
     */
    public function getTopLanguages(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:language',
                'sort' => '-ga:sessions, -ga:language'
            ]
        );
    }

    /**
     * @param GaParameters $gaParameters
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
                'dimensions' => 'ga:deviceCategory'
            ]
        );
    }

    /**
     * @param GaParameters $gaParameters
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
                'max-results' => 5
            ]
        );
    }
}