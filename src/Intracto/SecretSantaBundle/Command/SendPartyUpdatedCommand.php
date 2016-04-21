<?php

namespace Intracto\SecretSantaBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendPartyUpdatedCommand extends ContainerAwareCommand
{
    /**
     * Configure the command options.
     */
    protected function configure()
    {
        $this
            ->setName('intracto:sendPartyUpdatedMails')
            ->setDescription('Send notification to all participants if party has been updated');
    }

    /**
     * Execute the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        $poolQuery = $container->get('intracto_secret_santa.pool');
        $mailerService = $container->get('intracto_secret_santa.mail');
        $updatedPools = $poolQuery->findAllToNotifyOfUpdatedPartyMail();

        foreach ($updatedPools as $pool) {
            $mailerService->sendPoolUpdatedMailsForPool($pool);

            $pool->setDetailsUpdated(false);
            $em->persist($pool);
        }

        $em->flush();
    }
}
