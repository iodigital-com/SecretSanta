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
                'type',
                InputArgument::REQUIRED
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entryService = $this->getContainer()->get('intracto_secret_santa.entry');
        $previousYear = date('Y', strtotime('-1 year'));
        $season = new Season($previousYear);
        $type = $input->getArgument('type');

        switch ($type) {
            case 'admin':
                $entryService->fetchAdminEmailsForExport($season);

                break;
            case 'participant':
                $entryService->fetchParticipantEmailsForExport($season);
                break;
            default:
                $output->writeln('No valid parameter included');
                break;
        }
    }
}