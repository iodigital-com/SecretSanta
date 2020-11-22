<?php

namespace Intracto\SecretSantaBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Intracto\SecretSantaBundle\Query\ParticipantMailQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendPartyStatusCommand extends Command
{
    private EntityManagerInterface $em;
    private ParticipantMailQuery $participantMailQuery;
    private MailerService $mailerService;

    public function __construct(
        EntityManagerInterface $em,
        ParticipantMailQuery $participantMailQuery,
        MailerService $mailerService
    ) {
        $this->em = $em;
        $this->participantMailQuery = $participantMailQuery;
        $this->mailerService = $mailerService;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('intracto:sendPartyStatusMails')
            ->setDescription('Send party status mail to admins');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
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
