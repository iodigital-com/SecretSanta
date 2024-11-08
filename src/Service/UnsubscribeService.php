<?php

namespace App\Service;

use App\Entity\BlacklistEmail;
use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class UnsubscribeService
{
    public function __construct(
        public EntityManagerInterface $em,
        public RouterInterface $router,
        private readonly HashService $hashService,
    ) {
    }

    public function unsubscribe(Participant $participant, bool $fromAllParties): void
    {
        if ($fromAllParties) {
            /** @var ParticipantRepository $participantRepository */
            $participantRepository = $this->em->getRepository(Participant::class);
            /** @var Participant[] $participants */
            $participants = $participantRepository->findAllByEmail($participant->getEmail());
            foreach ($participants as $p) {
                $p->unsubscribe();
                $this->em->persist($participant);
            }
        } else {
            $participant->unsubscribe();
            $this->em->persist($participant);
        }
        $this->em->flush();
    }

    /**
     * Creates a link that can be used in the List-Unsubscribe header.
     */
    public function getUnsubscribeLink(Participant $participant): string
    {
        return '<'.$this->router->generate('unsubscribe_confirm', ['url' => $participant->getUrl(), '_locale' => $participant->getParty()->getLocale()], UrlGeneratorInterface::ABSOLUTE_URL).'>';
    }

    public function blacklist(Participant $participant, string $ip): void
    {
        // Unsubscribe participant from emails, with flag true for all parties.
        $this->unsubscribe($participant, true);
        $hashedMail = $this->hashService->hashEmail($participant->getEmail());
        $blacklist = new BlacklistEmail($hashedMail, $ip, new \DateTime());
        $this->em->persist($blacklist);
        $this->em->flush();
    }

    public function isBlacklisted(Participant $participant): bool
    {
        $hashedMail = $this->hashService->hashEmail($participant->getEmail());
        $repository = $this->em->getRepository(BlacklistEmail::class);
        $results = $repository->createQueryBuilder('b')
            ->where('b.email = :email')
            ->setParameter('email', $hashedMail)
            ->getQuery()
            ->getResult();

        return count($results) > 0;
    }
}
