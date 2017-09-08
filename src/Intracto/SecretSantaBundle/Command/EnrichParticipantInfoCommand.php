<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Intracto\SecretSantaBundle\Entity\ParticipantRepository;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class EnrichParticipantInfoCommand extends Command
{
    /** @var ParticipantRepository */
    private $participantRepository;
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(ParticipantRepository $participantRepository, EntityManagerInterface $em)
    {
        $this->participantRepository = $participantRepository;
        $this->em = $em;

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
        // Get the database at http://dev.maxmind.com/geoip/geoip2/geolite2/
        $reader = new Reader('/usr/local/share/GeoIP/GeoLite2-City.mmdb');

        foreach ($this->participantRepository->findAllParticipantsWithoutGeoInfo(100000) as $participant) {
            try {
                $geoInformation = $reader->city($participant->getIp());

                $participant->setGeoCountry($geoInformation->country->isoCode);
                $participant->setGeoProvince($geoInformation->mostSpecificSubdivision->isoCode);
                $participant->setGeoCity($geoInformation->city->name);
            } catch (AddressNotFoundException $ex) {
                $participant->setGeoCountry('');
                $participant->setGeoProvince('');
                $participant->setGeoCity('');
            }

            $this->em->persist($participant);
        }

        $this->em->flush();
    }
}
