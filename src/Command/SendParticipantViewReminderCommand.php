<?php

namespace App\Command;

use App\Mailer\MailerService;
use App\Query\ParticipantMailQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendParticipantViewReminderCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ParticipantMailQuery $participantMailQuery,
        private readonly MailerService $mailerService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:sendParticipantViewReminderMails')
            ->setDescription('Send reminder to participants to confirm their presence at the party');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
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

        return 0;
    }
}
