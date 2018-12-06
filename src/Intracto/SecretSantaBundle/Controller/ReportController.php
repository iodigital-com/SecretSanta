<?php

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Service\ExportReportQueriesService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

        return $exportReportQueriesService->getReportQuery($year);
    }

}
