<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Participant;
use App\Entity\Party;
use App\Repository\PartyRepository;
use ContainerCUCixN3\EntityManager_9a5be93;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class JoinApiTest extends WebTestCase
{
    public const TEST_JOIN_URL = 'ct1w8m2zf3co488gg400k80k80kkoog';

    protected function setUp(): void
    {
        self::bootKernel();

        // TODO: We need a better way to setup the test data.
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);
        /** @var PartyRepository $partyRepository */
        $partyRepository = self::$container->get(PartyRepository::class);

        $existing = $partyRepository->findOneBy(['joinurl' => self::TEST_JOIN_URL]);
        if ($existing instanceof Party) {
            $entityManager->remove($existing);
            $entityManager->flush();
        }

        $party = new Party(false);
        $party->setOwnerName('Filip');
        $party->setOwnerEmail('filip@belgium.be');
        $party->setEventdate(new \DateTime('2022-12-31T00:00:00+01:00'));
        $party->setAmount('15 eur');
        $party->setLocation('Brussels');
        $party->setMessage('Party party! 🎉🎉');
        $party->setLocale('en');
        $party->setJoinurl(self::TEST_JOIN_URL);
        $party->setJoinmode(1);

        $participant1 = new Participant();
        $participant1->setName('Filip');
        $participant1->setEmail('filip@belgium.be');

        $participant2 = new Participant();
        $participant2->setName('Mathilde');
        $participant2->setEmail('mathilde@belgium.be');

        $participant3 = new Participant();
        $participant3->setName('Elisabeth');
        $participant3->setEmail('elisabeth@belgium.be');

        $party->addParticipant($participant1);
        $party->addParticipant($participant2);
        $party->addParticipant($participant3);

        $entityManager->persist($party);
        $entityManager->flush();

        self::ensureKernelShutdown();
    }

    /** @test */
    public function itRetrievesInfoForParticipants(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/join/'.self::TEST_JOIN_URL);

        $this->assertResponseIsSuccessful();
        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $expectedData = [
            'ownerName' => 'Filip',
            'ownerEmail' => 'filip@belgium.be',
            'eventDate' => '2022-12-31T00:00:00+01:00',
            'amount' => '15 eur',
            'location' => 'Brussels',
            'message' => 'Party party! 🎉🎉',
            'locale' => 'en',
        ];

        $this->assertEquals($expectedData, $responseData);
    }
}