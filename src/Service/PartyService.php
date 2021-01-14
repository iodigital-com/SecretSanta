<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Party;
use App\Mailer\MailerService;

class PartyService
{
    /**
     * @var MailerService
     */
    private $mailerService;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ParticipantService
     */
    private $participantService;

    public function __construct(MailerService $mailerService, EntityManagerInterface $em, ParticipantService $participantService)
    {
        $this->mailerService = $mailerService;
        $this->em = $em;
        $this->participantService = $participantService;
    }

    public function startParty(Party $party): bool
    {
        if ($party->getCreated() || $party->getParticipants()->count() < 3) {
            return false;
        }

        $party->setCreated(true);
        $this->em->persist($party);

        $this->participantService->shuffleParticipants($party);

        $this->em->flush();

        $this->mailerService->sendSecretSantaMailsForParty($party);

        return true;
    }
}
