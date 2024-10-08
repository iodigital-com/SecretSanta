<?php

namespace App\Command;

use App\Query\Season;
use App\Service\ExportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportMailsCommand extends Command
{
    private ExportService $exportService;

    public function __construct(ExportService $exportService)
    {
        parent::__construct();
        $this->exportService = $exportService;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:export-for-mailing')
            ->setDescription('Export email addresses from database to use in mailing')
            ->addArgument(
                'userType',
                InputArgument::REQUIRED,
                'User type to export: admin or participant'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lastSeason = (int) date('Y', strtotime('-1 year'));
        $season = new Season($lastSeason);
        $userType = $input->getArgument('userType');

        switch ($userType) {
            case 'admin':
                $this->exportService->export($season, true);
                $output->writeln("Last season's admin emails exported to /tmp");

                break;
            case 'participant':
                $this->exportService->export($season, false);
                $output->writeln("Last season's participant emails exported to /tmp");

                break;
            default:
                $output->writeln('<error>Not a valid userType!</error>');

                break;
        }

        return 0;
    }
}
