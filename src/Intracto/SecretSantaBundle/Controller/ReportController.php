<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends Controller
{
    /**
     * @Route("/report", name="report")
     * @Template()
     */
    public function reportAction(Request $request)
    {
        $reportQuery = $this->get('intracto_secret_santa.report');
        $analyticsQuery = $this->get('intracto_secret_santa.analytics');
        $currentYear = $request->get('year', 'all');
        $viewId = $this->container->getParameter('ga_view_id');

        try {
            if ($currentYear != 'all') {
                $dataPool = $reportQuery->getPoolReport($currentYear);

            } else {
                $dataPool = $reportQuery->getPoolReport();

            }
        } catch (\Exception $e) {
            $dataPool = [];
        }

        try {
            if ($currentYear != 'all') {
                $googleDataPool = $analyticsQuery->getAnalyticsReport($viewId, $currentYear);
            } else {
                $googleDataPool = $analyticsQuery->getAnalyticsReport($viewId);
            }
        } catch (\Exception $e) {
            $googleDataPool = [];
        }

        return [
            'current_year' => $currentYear,
            'data_pool' => $dataPool,
            'featured_years' => $this->get('intracto_secret_santa.featured_years')->getFeaturedYears(),
            'google_data_pool' => $googleDataPool,
        ];
    }
}