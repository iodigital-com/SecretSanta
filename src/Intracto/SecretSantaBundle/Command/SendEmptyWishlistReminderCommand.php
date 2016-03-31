<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmptyWishlistReminderCommand extends ContainerAwareCommand
{
    /**
     * Configure the command options
     */
    protected function configure()
    {
        $this
            ->setName('intracto:sendWishlistReminderMails')
            ->setDescription('Send reminder to add items to wishlist');
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
        $emptyWishlists = $entryQuery->findAllToRemindOfEmptyWishlist();

        foreach ($emptyWishlists as $entry) {
            $mailerService->sendWishlistReminderMail($entry);
        }
    }
}