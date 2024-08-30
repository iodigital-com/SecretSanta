<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Party;
use App\Mailer\MailerService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PartyService
{
    public function __construct(
		private MailerService $mailerService,
		private EntityManagerInterface $em,
		private ParticipantService $participantService)
    {}

	/**
	 * @throws TransportExceptionInterface
	 */
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
