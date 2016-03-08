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
        $report = $this->get('intracto_secret_santa.report');
        $comparison = $this->get('intracto_secret_santa.season_comparison');
        $currentYear = $request->get('year', 'all');

        try {
            if ($currentYear != 'all') {
                $dataPool = $report->getPoolReport($currentYear);
                $differenceDataPool = $comparison->getComparison($currentYear);
            } else {
                $dataPool = $report->getPoolReport();
                $differenceDataPool = [];
            }
        } catch (\Exception $e) {
            $currentYear = [];
            $dataPool = [];
            $differenceDataPool = [];
        }

        return [
            'current_year' => $currentYear,
            'data_pool' => $dataPool,
            'featured_years' => $this->get('intracto_secret_santa.featured_years')->getFeaturedYears(),
            'difference_data_pool' => $differenceDataPool,
        ];
    }
}