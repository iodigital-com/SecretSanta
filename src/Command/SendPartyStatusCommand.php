<?php

namespace App\Command;

use App\Entity\Participant;
use App\Mailer\MailerService;
use App\Query\ParticipantMailQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendPartyStatusCommand extends Command
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
            ->setName('app:sendPartyStatusMails')
            ->setDescription('Send party status mail to admins');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Participant[] $partyAdmins */
        $partyAdmins = $this->participantMailQuery->findAllAdminsForPartyStatusMail();
        $timeNow = new \DateTime();

        try {
            foreach ($partyAdmins as $partyAdmin) {
                $this->mailerService->sendPartyStatusMail($partyAdmin);

                $partyAdmin->setPartyStatusSentTime($timeNow);
                $this->em->persist($partyAdmin);
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
