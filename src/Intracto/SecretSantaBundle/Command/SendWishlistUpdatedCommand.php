<?php

namespace Intracto\SecretSantaBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Intracto\SecretSantaBundle\Query\ParticipantMailQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendWishlistUpdatedCommand extends Command
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
            ->setName('intracto:sendWishlistUpdatedMails')
            ->setDescription('Send notification to buddy to alert them the wishlist has been updated');
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
        /** @var Participant[] $secretSantas */
        $secretSantas = $this->participantMailQuery->findAllToRemindOfUpdatedWishlist();
        $timeNow = new \DateTime();

        try {
            foreach ($secretSantas as $secretSanta) {
                $receiver = $secretSanta->getAssignedParticipant();

                $this->mailerService->sendWishlistUpdatedMail($receiver, $secretSanta);

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
    }
}
