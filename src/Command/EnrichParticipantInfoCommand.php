<?php

namespace App\Command;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnrichParticipantInfoCommand extends Command
{
    public function __construct(
        private ParticipantRepository $participantRepository,
        private EntityManagerInterface $em,
        private string $geoIpDbPath,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:enrich:participants')
            ->setDescription('Enrich the participant information');
    }

    /**
     * @throws InvalidDatabaseException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $batchSize = 1000;
        $reader = new Reader($this->geoIpDbPath);

        $participants = $this->participantRepository->findAllParticipantsWithoutGeoInfo($batchSize);
        while (\count($participants) > 0) {
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

        return 0;
    }
}
