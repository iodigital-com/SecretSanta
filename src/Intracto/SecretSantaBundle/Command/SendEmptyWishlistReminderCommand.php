<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

class SendEmptyWishlistReminderCommand extends ContainerAwareCommand
{
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
        $container = $this->getContainer();
        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        /** @var \Intracto\SecretSantaBundle\Query\ParticipantMailQuery $participantMailQuery */
        $participantMailQuery = $container->get('intracto_secret_santa.query.participant_mail');
        /** @var \Intracto\SecretSantaBundle\Query\WishlistMailQuery $wishlistMailQuery */
        $wishlistMailQuery = $container->get('intracto_secret_santa.query.wishlist_mail');
        /** @var \Intracto\SecretSantaBundle\Mailer\MailerService $mailerService */
        $mailerService = $container->get('intracto_secret_santa.mailer');

        $emptyWishlistsParticipant = $participantMailQuery->findAllToRemindOfEmptyWishlist();
        $timeNow = new \DateTime();

        try {
            foreach ($emptyWishlistsParticipant as $participant) {
                $itemCount = $wishlistMailQuery->countWishlistItemsOfParticipant($participant);

                if ($itemCount[0]['wishlistItemCount'] == 0) {
                    $mailerService->sendWishlistReminderMail($participant);

                    $participant->setEmptyWishlistReminderSentTime($timeNow);
                    $em->persist($participant);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $em->flush();
        }
    }
}
