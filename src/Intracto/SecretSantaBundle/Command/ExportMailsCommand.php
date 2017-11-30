<?php

namespace Intracto\SecretSantaBundle\Command;

use Intracto\SecretSantaBundle\Query\ParticipantReportQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Intracto\SecretSantaBundle\Query\Season;

class ExportMailsCommand extends Command
{
    private $participantReportQuery;

    public function __construct(ParticipantReportQuery $participantReportQuery)
    {
        $this->participantReportQuery = $participantReportQuery;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('intracto:exportmails')
            ->setDescription('Export mails from database')
            ->addArgument(
                'userType',
                InputArgument::OPTIONAL
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lastSeason = date('Y', strtotime('-1 year'));
        $season = new Season($lastSeason);
        $userType = $input->getArgument('userType');

        switch ($userType) {
            case 'admin':
                $this->participantReportQuery->fetchMailsForExport($season, true);
                $output->writeln("Last season's admin emails exported to /tmp");
                break;
            case 'participant':
                $this->participantReportQuery->fetchMailsForExport($season, false);
                $output->writeln("Last season's participant emails exported to /tmp");
                break;
            default:
                $this->participantReportQuery->fetchMailsForExport($season, true);
                $this->participantReportQuery->fetchMailsForExport($season, false);
                $output->writeln('All emails exported to /tmp');
                break;
        }
    }
}
