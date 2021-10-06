<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Service\ExportReportQueriesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report/{year}", defaults={"year" = "all"}, name="report", methods={"GET"})
     * @Template("Report/index.html.twig")
     */
    public function indexAction(ExportReportQueriesService $exportReportQueriesService, string $year)
    {
        if ('all' !== $year) {
            if (false === strtotime($year)) {
                $year = date('Y');
            }
        }

        return $exportReportQueriesService->getReportQuery($year);
    }
}
