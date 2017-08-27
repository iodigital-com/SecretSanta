<?php

namespace Intracto\SecretSantaBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Intracto\SecretSantaBundle\Query\ParticipantMailQuery;
use Intracto\SecretSantaBundle\Query\WishlistMailQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

class SendEmptyWishlistReminderCommand extends Command
{
    private $em;
    private $participantMailQuery;
    private $wishlistMailQuery;
    private $mailerService;

    public function __construct(
        EntityManagerInterface $em,
        ParticipantMailQuery $participantMailQuery,
        WishlistMailQuery $wishlistMailQuery,
        MailerService $mailerService
    )
    {
        $this->em = $em;
        $this->participantMailQuery = $participantMailQuery;
        $this->wishlistMailQuery = $wishlistMailQuery;
        $this->mailerService = $mailerService;

        parent::__construct();
    }

    /**
     * Configure the command options.
     */
    protected function configure()
    {
        $this
            ->setName('intracto:sendWishlistReminderMails')
            ->setDescription('Send reminder to add items to wishlist');
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
    }
}
