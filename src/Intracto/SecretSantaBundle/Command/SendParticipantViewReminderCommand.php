<?php

namespace Intracto\SecretSantaBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Intracto\SecretSantaBundle\Query\ParticipantMailQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendParticipantViewReminderCommand extends Command
{
    private $em;
    private $participantMailQuery;
    private $mailerService;

    public function __construct(
        EntityManagerInterface $em,
        ParticipantMailQuery $participantMailQuery,
        MailerService $mailerService
    )
    {
        $this->em = $em;
        $this->participantMailQuery = $participantMailQuery;
        $this->mailerService = $mailerService;

        parent::__construct();
    }

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
        $needsViewReminder = $this->participantMailQuery->findAllToRemindToViewParticipant();
        $timeNow = new \DateTime();

        try {
            foreach ($needsViewReminder as $participant) {
                $this->mailerService->sendParticipantViewReminderMail($participant);

                $participant->setViewReminderSentTime($timeNow);
                $this->em->persist($participant);
            }

            $this->em->flush();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $this->em->flush();
        }
    }
}
