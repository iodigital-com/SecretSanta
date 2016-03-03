<?php

namespace Intracto\SecretSantaBundle\Query;

use Google_Client;
use Google_Service_Analytics;

class GoogleAnalyticsQueries
{
    public function getFullAnalyticsReport()
    {
        $client = new Google_Client();
        $credentials = $client->loadServiceAccountJson('../client_secrets.json', "https://www.googleapis.com/auth/analytics.readonly");
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        $analytics = new Google_Service_Analytics($client);
        $analyticsViewId = 'ga:114986929';
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

    public function getTopCountries($analytics, $analyticsViewId, $startDate, $endDate, $metrics)
    {
        return $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
            'dimensions' => 'ga:country'
        ));
    }

    public function getTopLanguages($analytics, $analyticsViewId, $startDate, $endDate, $metrics)
    {
        return $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
            'dimensions' => 'ga:language'
        ));
    }

    public function getDeviceCategory($analytics, $analyticsViewId, $startDate, $endDate, $metrics)
    {
        return $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
            'dimensions' => 'ga:deviceCategory'
        ));
    }

    public function getBrowsers($analytics, $analyticsViewId, $startDate, $endDate, $metrics)
    {
        return $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
            'dimensions' => 'ga:browser'
        ));
    }

    public function getAnalyticsReport($year)
    {
        $client = new Google_Client();
        $credentials = $client->loadServiceAccountJson('../client_secrets.json', "https://www.googleapis.com/auth/analytics.readonly");
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        $analytics = new Google_Service_Analytics($client);
        $analyticsViewId = 'ga:114986929';
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