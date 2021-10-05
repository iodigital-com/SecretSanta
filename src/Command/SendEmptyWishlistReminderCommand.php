<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Mailer\MailerService;
use App\Query\ParticipantMailQuery;
use App\Query\WishlistMailQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmptyWishlistReminderCommand extends Command
{
    private EntityManagerInterface $em;
    private ParticipantMailQuery $participantMailQuery;
    private WishlistMailQuery $wishlistMailQuery;
    private MailerService $mailerService;

    public function __construct(
        EntityManagerInterface $em,
        ParticipantMailQuery $participantMailQuery,
        WishlistMailQuery $wishlistMailQuery,
        MailerService $mailerService
    ) {
        $this->em = $em;
        $this->participantMailQuery = $participantMailQuery;
        $this->wishlistMailQuery = $wishlistMailQuery;
        $this->mailerService = $mailerService;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:sendWishlistReminderMails')
            ->setDescription('Send reminder to add items to wishlist');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $emptyWishlistsParticipant = $this->participantMailQuery->findAllToRemindOfEmptyWishlist();
        $timeNow = new \DateTime();

        try {
            foreach ($emptyWishlistsParticipant as $participant) {
                $itemCount = $this->wishlistMailQuery->countWishlistItemsOfParticipant($participant);

                if ($itemCount[0]['wishlistItemCount'] == 0) {
                    $this->mailerService->sendWishlistReminderMail($participant);

                    $participant->setEmptyWishlistReminderSentTime($timeNow);
                    $this->em->persist($participant);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $this->em->flush();
        }

        return 0;
    }
}
