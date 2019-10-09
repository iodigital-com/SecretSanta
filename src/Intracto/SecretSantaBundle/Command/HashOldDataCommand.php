<?php

namespace Intracto\SecretSantaBundle\Command;

use Intracto\SecretSantaBundle\Entity\BlacklistEmail;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Service\HashService;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Intracto\SecretSantaBundle\Repository\ParticipantRepository;

class HashOldDataCommand extends Command
{
    /** @var ParticipantRepository */
    private $participantRepository;

    /** @var EntityManagerInterface */
    private $em;

    /** @var HashService $hashService */
    private $hashService;

    public function __construct(
        ParticipantRepository $participantRepository,
        EntityManagerInterface $em,
        HashService $hashService
    ) {
        $em
            ->getConnection()
            ->getConfiguration()
            ->setSQLLogger(null)
        ;

        $this->participantRepository = $participantRepository;
        $this->em = $em;
        $this->hashService = $hashService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('intracto:hash-participants')
            ->setDescription('Hash (old) participants.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $blackListRepository = $this->em->getRepository(BlacklistEmail::class);

        $nextId = 0;

        $qb = $this->em->createQueryBuilder();
        $qb->select('p')
           ->from('IntractoSecretSantaBundle:Participant', 'p')
           ->where('p.id > :id')
           ->andWhere('p.isHashed = false')
           ->setParameter('id', $nextId)
           ->setMaxResults(10000);

        /** @var Participant[] $participants */
        $participants = $qb->getQuery()->getResult();

        while (!empty($participants)) {
            foreach ($participants as $participant) {
                $nextId = $participant->getId();
                $twoYearsAgo = new \DateTime();
                $twoYearsAgo->setTime(0, 0);
                $twoYearsAgo->sub(new \DateInterval('P2Y'));

                // Check if blacklisted
                $hashedEmail = $this->hashService->hashEmail($participant->getEmail());
                $blackListedMail = $blackListRepository->findOneBy(['email' => $hashedEmail]);

                // Hash all black listed
                $isBlackListed = (null !== $blackListedMail);

                // Hash non-admin if party was one year ago.
                $nonAdmin = (!$participant->isPartyAdmin() && $participant->getParty()->getEventdate() <= $twoYearsAgo);

                // Hash all unsubscribed
                $unSubscribed = (!$participant->isSubscribed());

                if ($isBlackListed || $nonAdmin || $unSubscribed) {
                    $this->hashParticipant($participant);
                }
            }

            $this->em->flush();

            echo '[next id = ' . $nextId . ']';

            $qb = $this->em->createQueryBuilder();
            $qb->select('p')
               ->from('IntractoSecretSantaBundle:Participant', 'p')
               ->where('p.id > :id')
               ->andWhere('p.isHashed = false')
               ->setParameter('id', $nextId)
               ->setMaxResults(10000);

            /** @var Participant $participant */
            $participants = $qb->getQuery()->getResult();
        }

        $this->em->flush();
    }

    /**
     * @param Participant $participant
     */
    protected function hashParticipant(Participant $participant): void
    {
        if ($participant->isHashed()) {
            return;
        }

        $email = $participant->getEmail();
        $hashedEmail = $this->hashService->hashEmail($email);
        $participant->setEmail($hashedEmail);

        $hashedName = $this->hashService->hashString($participant->getName());
        $participant->setName($hashedName);
        $participant->setIsHashed(true);
    }
}
