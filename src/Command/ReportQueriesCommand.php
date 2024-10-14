<?php

namespace App\Command;

use App\Service\ExportReportQueriesService;
use App\Service\ReportQueriesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReportQueriesCommand extends Command
{
    private ReportQueriesService $reportQueriesService;
    private ExportReportQueriesService $exportReportQueriesService;

    public function __construct(ReportQueriesService $reportQueriesService, ExportReportQueriesService $exportReportQueriesService)
    {
        parent::__construct();
        $this->reportQueriesService = $reportQueriesService;
        $this->exportReportQueriesService = $exportReportQueriesService;
    }

    protected function configure()
    {
        $this
            ->setName('app:report-queries')
            ->setDescription('Export report queries')
            ->addArgument(
                'year',
                InputArgument::REQUIRED,
                'Export queries for a given year. Expected a year or the string "all"'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = $input->getArgument('year');

        if ('all' !== $year) {
            if (false === strtotime($year)) {
                $year = date('Y');
            }
        }

        $this->exportReportQueriesService->export($this->reportQueriesService->getReportResults($year), $year);

        return 0;
    }
}
