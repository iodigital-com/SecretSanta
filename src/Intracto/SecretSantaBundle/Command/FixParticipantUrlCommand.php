<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Intracto\SecretSantaBundle\Entity\ParticipantRepository;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class FixParticipantUrlCommand extends Command
{
    /** @var ParticipantRepository */
    private $participantRepository;
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        ParticipantRepository $participantRepository,
        EntityManagerInterface $em
    ) {
        $this->participantRepository = $participantRepository;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('intracto:fix:participant_info');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $participants = $this->participantRepository->findBy(['url' => null]);
        foreach ($participants as $participant) {
            if (!empty($participant->getUrl())) {
                echo 'This should not happen!';
                die();
            }
            $participant->setUrl(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));

            $this->em->persist($participant);
            $this->em->flush();
        }

    }
}
