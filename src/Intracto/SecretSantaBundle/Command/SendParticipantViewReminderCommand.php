<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

class SendParticipantViewReminderCommand extends ContainerAwareCommand
{
    /**
     * Configure the command options.
     */
    protected function configure()
    {
        $this
            ->setName('intracto:sendParticipantViewReminderMails')
            ->setDescription('Send reminder to participants to confirm their presence at the party');
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
        $participantMailQuery = $container->get('intracto_secret_santa.query.participant_mailer');
        $mailerService = $container->get('intracto_secret_santa.mailer');
        $needsViewReminder = $participantMailQuery->findAllToRemindToViewEntry();
        $timeNow = new \DateTime();

        try {
            foreach ($needsViewReminder as $participant) {
                $mailerService->sendEntryViewReminderMail($participant);

                $participant->setViewReminderSentTime($timeNow);
                $em->persist($participant);
            }

            $em->flush();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $em->flush();
        }
    }
}
