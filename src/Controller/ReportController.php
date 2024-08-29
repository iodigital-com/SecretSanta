<?php

namespace App\Controller;

use App\Service\ExportReportQueriesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends AbstractController
{
	#[Route("/{_locale}/report/{year}", name: "report", defaults: ["year" => "all"], methods: ["GET"])]
    public function indexAction(ExportReportQueriesService $exportReportQueriesService, string $year): Response
	{
        if ('all' !== $year) {
            if (false === strtotime($year)) {
                $year = date('Y');
            }
        }

        return $this->render('Report/index.html.twig', $exportReportQueriesService->getReportQuery($year));
    }
}
