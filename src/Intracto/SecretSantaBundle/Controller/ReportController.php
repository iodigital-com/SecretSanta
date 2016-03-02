<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Google_Client;
use Google_Service_Analytics;

class ReportController extends Controller
{
    /**
     * @Route("/report", name="report")
     * @Template()
     */
    public function reportAction(Request $request)
    {
        $reportQuery = $this->get('intracto_secret_santa.reporting');
        $featuredYears = $reportQuery->getFeaturedYears();
        $currentYear = $request->get('year', 'all');

        try {
            if ($currentYear != 'all') {
                $dataPool = $reportQuery->getPoolReport($currentYear);
            } else {
                $dataPool = $reportQuery->getFullPoolReport();
            }
        } catch (\Exception $e) {
            $currentYear = [];
            $dataPool = [];
        }

                $client = new Google_Client();
                $credentials = $client->loadServiceAccountJson('../client_secrets.json', "https://www.googleapis.com/auth/analytics.readonly");
                $client->setAssertionCredentials($credentials);
                if ($client->getAuth()->isAccessTokenExpired()) {
                    $client->getAuth()->refreshTokenWithAssertion();
                }

         $analytics = new Google_Service_Analytics($client);

         // Add Analytics View ID, prefixed with "ga:"
         $analyticsViewId = 'ga:114986929';

         $startDate = '2016-02-01';
         $endDate = '2016-02-29';
         $metrics = 'ga:sessions,ga:pageviews';

         $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
                 'dimensions' => 'ga:pagePath',
                 'sort' => '-ga:pageviews',
             ));

         // Data
         $items = $data->totalsForAllResults;

         //var_dump($items['ga:sessions']);
        return [
            'current_year' => $currentYear,
            'data_pool' => $dataPool,
            'featured_years' => $featuredYears,
            'items' => $items,
        ];
    }
}