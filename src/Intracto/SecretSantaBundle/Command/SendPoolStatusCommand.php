<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendPoolStatusCommand extends ContainerAwareCommand
{
    /**
     * Configure the command options
     */
    protected function configure()
    {
        $this
            ->setName('intracto:sendpoolstatusmails')
            ->setDescription('Send pool status mail to admins');
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $entryQuery = $container->get('intracto_secret_santa.entry');
        $mailerService = $container->get('intracto_secret_santa.mail');
        $poolAdmins = $entryQuery->findAllAdminsForPoolStatusMail();

        foreach ($poolAdmins as $poolAdmin) {
            $mailerService->sendPoolStatusMail($poolAdmin);
        }
    }
}