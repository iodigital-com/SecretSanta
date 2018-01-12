<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Intracto\SecretSantaBundle\Repository\ParticipantRepository;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class EnrichParticipantInfoCommand extends Command
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
            ->setName('intracto:enrich:participants')
            ->setDescription('Enrich the participant information');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batchSize = 1000;
        $reader = new Reader($this->geoIpDbPath);

        $participants = $this->participantRepository->findAllParticipantsWithoutGeoInfo($batchSize);
        while (count($participants) > 0) {
            foreach ($participants as $participant) {
                try {
                    $geoInformation = $reader->city($participant->getIp());

                    $participant->setGeoCountry($geoInformation->country->isoCode);
                    $participant->setGeoProvince($geoInformation->mostSpecificSubdivision->isoCode);
                    $participant->setGeoCity($geoInformation->city->name);
                } catch (AddressNotFoundException $ex) {
                    $participant->setGeoCountry('');
                }

                if (empty($participant->getGeoCountry())) {
                    $participant->setGeoCountry('');
                    $participant->setGeoProvince('');
                    $participant->setGeoCity('');
                }

                $this->em->persist($participant);
            }

            $this->em->flush();
            $participants = $this->participantRepository->findAllParticipantsWithoutGeoInfo($batchSize);
        }
    }
}
