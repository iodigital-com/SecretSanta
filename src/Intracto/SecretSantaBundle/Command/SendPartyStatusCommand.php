<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

class SendPartyStatusCommand extends ContainerAwareCommand
{
    /**
     * Configure the command options.
     */
    protected function configure()
    {
        $this
            ->setName('intracto:sendPartyStatusMails')
            ->setDescription('Send party status mail to admins');
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
        /** @var \Intracto\SecretSantaBundle\Query\ParticipantMailQuery $participantMailQuery */
        $participantMailQuery = $container->get('intracto_secret_santa.query.participant_mail');
        $mailerService = $container->get('intracto_secret_santa.mailer');
        $partyAdmins = $participantMailQuery->findAllAdminsForPartyStatusMail();
        $timeNow = new \DateTime();

        try {
            foreach ($partyAdmins as $partyAdmin) {
                $mailerService->sendPartyStatusMail($partyAdmin);

                $partyAdmin->setPartyStatusSentTime($timeNow);
                $em->persist($partyAdmin);
            }

            $em->flush();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $em->flush();
        }
    }
}
