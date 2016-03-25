<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
        $entryService = $this->getContainer()->get('intracto_secret_santa.entry');
        $lastSeason = date('Y', strtotime('-1 year'));
        $season = new Season($lastSeason);
        $userType = $input->getArgument('userType');

        switch ($userType) {
            case 'admin':
                $entryService->fetchAdminEmailsForExport($season);
                $output->writeln("Last season's admin emails exported to /export/admin");

                break;
            case 'participant':
                $entryService->fetchParticipantEmailsForExport($season);
                $output->writeln("Last season's participant emails exported to /export/participant");
                break;
            default:
                $entryService->fetchAdminEmailsForExport($season);
                $entryService->fetchParticipantEmailsForExport($season);
                $output->writeln("All emails exported to /export/admin & /export/participant");
                break;
        }
    }
}