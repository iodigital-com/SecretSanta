<?php

namespace Intracto\SecretSantaBundle\Command;

use Intracto\SecretSantaBundle\Service\ExportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Intracto\SecretSantaBundle\Query\Season;

class ExportMailsCommand extends Command
{
    /**
     * @var ExportService
     */
    private $exportService;

    /**
     * @param ExportService $exportService
     */
    public function __construct(ExportService $exportService)
    {
        parent::__construct();
        $this->exportService = $exportService;
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lastSeason = date('Y', strtotime('-1 year'));
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
                $this->exportService->export($season, true);
                $this->exportService->export($season, false);
                $output->writeln('All emails exported to /tmp');
                break;
        }
    }
}
