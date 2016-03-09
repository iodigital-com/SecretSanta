<?php

namespace Intracto\SecretSantaBundle\Query;

use Google_Client;
use Google_Service_Analytics;

class GoogleAnalyticsQueries
{
    /**
     * @return array
     * @throws \Google_Exception
     */
    public function getFullAnalyticsReport()
    {
        $client = new Google_Client();
        $credentials = $client->loadServiceAccountJson('../app/config/client_secrets.json', "https://www.googleapis.com/auth/analytics.readonly");
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        $analytics = new Google_Service_Analytics($client);
        $analyticsViewId = 'ga:66807874';
        $startDate = '2012-04-01';
        $endDate = date('Y-m-d');
        $metrics = 'ga:sessions';

        return [
            'countries' => $this->getTopCountries($analytics, $analyticsViewId, $startDate, $endDate, $metrics)->rows,
            'language' => $this->getTopLanguages($analytics, $analyticsViewId, $startDate, $endDate, $metrics)->rows,
            'deviceCategory' => $this->getDeviceCategory($analytics, $analyticsViewId, $startDate, $endDate, $metrics)->rows,
            'browser' => $this->getBrowsers($analytics, $analyticsViewId, $startDate, $endDate, $metrics)->rows,
        ];
    }

    /**
     * @param $analytics
     * @param $analyticsViewId
     * @param $startDate
     * @param $endDate
     * @param $metrics
     * @return mixed
     */
    public function getTopCountries($analytics, $analyticsViewId, $startDate, $endDate, $metrics)
    {
        return $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
            'dimensions' => 'ga:country',
            'sort' => '-ga:country'
        ));
    }

    /**
     * @param $analytics
     * @param $analyticsViewId
     * @param $startDate
     * @param $endDate
     * @param $metrics
     * @return mixed
     */
    public function getTopLanguages($analytics, $analyticsViewId, $startDate, $endDate, $metrics)
    {
        return $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
            'dimensions' => 'ga:language',
            'sort' => '-ga:language'
        ));
    }

    /**
     * @param $analytics
     * @param $analyticsViewId
     * @param $startDate
     * @param $endDate
     * @param $metrics
     * @return mixed
     */
    public function getDeviceCategory($analytics, $analyticsViewId, $startDate, $endDate, $metrics)
    {
        return $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
            'dimensions' => 'ga:deviceCategory'
        ));
    }

    /**
     * @param $analytics
     * @param $analyticsViewId
     * @param $startDate
     * @param $endDate
     * @param $metrics
     * @return mixed
     */
    public function getBrowsers($analytics, $analyticsViewId, $startDate, $endDate, $metrics)
    {
        return $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
            'dimensions' => 'ga:browser',
            'sort' => '-ga:browser',
            'max-results' => 5
        ));
    }

    /**
     * @param $year
     * @return array
     * @throws \Google_Exception
     */
    public function getAnalyticsReport($year)
    {
        $client = new Google_Client();
        $credentials = $client->loadServiceAccountJson('../app/config/client_secrets.json', "https://www.googleapis.com/auth/analytics.readonly");
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        $analytics = new Google_Service_Analytics($client);
        $analyticsViewId = 'ga:66807874';
        $startDate = $year . '-04-01';
        $endDate = $year + 1 . '-04-01';
        $metrics = 'ga:sessions';

        return [
            'countries' => $this->getTopCountries($analytics, $analyticsViewId, $startDate, $endDate, $metrics)->rows,
            'language' => $this->getTopLanguages($analytics, $analyticsViewId, $startDate, $endDate, $metrics)->rows,
            'deviceCategory' => $this->getDeviceCategory($analytics, $analyticsViewId, $startDate, $endDate, $metrics)->rows,
            'browser' => $this->getBrowsers($analytics, $analyticsViewId, $startDate, $endDate, $metrics)->rows,
        ];
    }
}