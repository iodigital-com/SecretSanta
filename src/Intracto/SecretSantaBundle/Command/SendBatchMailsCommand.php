<?php

namespace Intracto\SecretSantaBundle\Command;

use Intracto\SecretSantaBundle\Mailer\Mail\BatchEmptyWishlistReminder;
use Intracto\SecretSantaBundle\Mailer\Mail\BatchViewEntryReminder;
use Intracto\SecretSantaBundle\Mailer\Mailer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendBatchMailsCommand extends ContainerAwareCommand
{
    /**
     * Configure the command options
     */
    protected function configure()
    {
        $this
            ->setName('intracto:mails:reminders:send')
            ->setDescription('Send reminder mails')
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'If not set, a trial run will execute. No mails will be actually sent'
            )
            ->addOption(
                'batch-size',
                null,
                InputOption::VALUE_OPTIONAL,
                'Send this amount of messages at a time (defaults to 10)',
                10
            )
            ->addOption(
                'time-limit',
                null,
                InputOption::VALUE_OPTIONAL,
                'Stop processing after this amount of seconds. If not given, a single batch is processed.',
                0
            );
    }

    /**
     * Execute the command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set context for URL generation
        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost($this->getContainer()->getParameter('base_url'));

        /** @var Mailer $mailer */
        $mailer = $this->getContainer()->get('intracto_secret_santa.mailer');

        $mailer->setOutput($output);

        $batchSize = $input->getOption("batch-size");
        $timeLimit = $input->getOption("time-limit");

        if (!is_numeric($batchSize) || $batchSize < 1) throw new \LogicException("batch size invalid");
        if (!is_numeric($timeLimit) || $timeLimit < 0) throw new \LogicException("timelimit invalid");

        $started = time();
        do {
            $mailer->sendBatchMails(
                $this->getContainer()->getParameter('admin_email'),
                [
                    new BatchViewEntryReminder($batchSize),
//                    new BatchEmptyWishlistReminder($batchSize),
                ],
                $input->getOption('force')
            );

            // pause a bit
            sleep(5);

        } while ($timeLimit > (time() - $started));

    }
}
