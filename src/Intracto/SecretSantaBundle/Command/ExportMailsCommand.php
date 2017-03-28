<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Intracto\SecretSantaBundle\Query\Season;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ExportMailsCommand extends ContainerAwareCommand
{
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
        /** @var \Intracto\SecretSantaBundle\Query\ParticipantReportQuery $participantReportQuery */
        $participantReportQuery = $this->getContainer()->get('intracto_secret_santa.query.participant_report');
        $lastSeason = date('Y', strtotime('-1 year'));
        $season = new Season($lastSeason);
        $userType = $input->getArgument('userType');

        switch ($userType) {
            case 'admin':
                $participantReportQuery->fetchAdminEmailsForExport($season);
                $output->writeln("Last season's admin emails exported to /tmp");
                break;
            case 'participant':
                $participantReportQuery->fetchParticipantEmailsForExport($season);
                $output->writeln("Last season's participant emails exported to /tmp");
                break;
            default:
                $participantReportQuery->fetchAdminEmailsForExport($season);
                $participantReportQuery->fetchParticipantEmailsForExport($season);
                $output->writeln('All emails exported to /tmp');
                break;
        }
    }
}
