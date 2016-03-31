<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEntryViewReminderCommand extends ContainerAwareCommand
{
    /**
     * Configure the command options
     */
    protected function configure()
    {
        $this
            ->setName('intracto:sendEntryViewReminderMails')
            ->setDescription('Send reminder to participants to confirm their presence at the party')
            ->addArgument(
                'force',
                null,
                'If not set, a trial run will execute. No mails will be actually sent',
                false
            );
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
        $needsViewReminder = $entryQuery->findAllToRemindToViewEntry();

        foreach ($needsViewReminder as $entry) {
            $mailerService->sendEntryViewReminderMail($entry);
        }
    }
}