<?php

namespace App\Command;

use App\Entity\Participant;
use App\Mailer\MailerService;
use App\Query\ParticipantMailQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendWishlistUpdatedCommand extends Command
{
    private EntityManagerInterface $em;
    private ParticipantMailQuery $participantMailQuery;
    private MailerService $mailerService;

    public function __construct(
        EntityManagerInterface $em,
        ParticipantMailQuery $participantMailQuery,
        MailerService $mailerService,
    ) {
        $this->em = $em;
        $this->participantMailQuery = $participantMailQuery;
        $this->mailerService = $mailerService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:sendWishlistUpdatedMails')
            ->setDescription('Send notification to buddy to alert them the wishlist has been updated');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Participant[] $secretSantas */
        $secretSantas = $this->participantMailQuery->findAllToRemindOfUpdatedWishlist();
        $timeNow = new \DateTime();

        try {
            foreach ($secretSantas as $secretSanta) {
                $receiver = $secretSanta->getAssignedParticipant();

                $this->mailerService->sendWishlistUpdatedMail($secretSanta);

                $receiver->setWishlistUpdated(false);
                $receiver->setUpdateWishlistReminderSentTime($timeNow);
                $this->em->persist($receiver);
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
