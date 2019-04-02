<?php

namespace Intracto\SecretSantaBundle\Command;

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

    /** @var string */
    private $geoIpDbPath;

    public function __construct(
        ParticipantRepository $participantRepository,
        EntityManagerInterface $em,
        string $geoIpDbPath
    ) {
        $this->participantRepository = $participantRepository;
        $this->em = $em;
        $this->geoIpDbPath = $geoIpDbPath;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('intracto:hash-data')
            ->setDescription('Hash old participants and parties.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $participantsCount = $this->participantRepository->countNonAdminParticipantsParticipatedOneYearAgo();

    }
}