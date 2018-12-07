<?php

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Intracto\SecretSantaBundle\Service\ExportReportQueriesService;

class ReportController extends Controller
{
    /**
     * @Route("/report/{year}", defaults={"year" = "all"}, name="report")
     * @Template()
     * @Method("GET")
     */
    public function indexAction(string $year)
    {
        /** @var ExportReportQueriesService $exportReportQueriesService */
        $exportReportQueriesService = $this->get('intracto_secret_santa.service.export_report_queries');

        if ('all' !== $year) {
            if (false === strtotime($year)) {
                $year = date('Y');
            }
        }

        return $exportReportQueriesService->getReportQuery($year);
    }
}
