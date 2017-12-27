<?php

namespace Intracto\Behat\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Intracto\Behat\Features\Context\FeatureContext;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Entity\Party;
use Intracto\SecretSantaBundle\Entity\WishlistItem;

class LoadPartyData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->loadStartedParty($manager);
        $this->loadCreatedParty($manager);
    }

    private function loadStartedParty(ObjectManager $manager)
    {
        // Party started by the administrator
        $party = new Party(false);

        $party->setListurl(FeatureContext::STARTED_PARTY_URL_TOKEN);
        $party->setWishlistsurl(FeatureContext::STARTED_PARTY_WISHLISTS_URL_TOKEN);
        $party->setAmount(100);

        $eventDate = (new \DateTime())->add(new \DateInterval('P2M'));

        $party->setEventdate($eventDate);
        $party->setLocation('Test');
        $party->setMessage('Test message');
        $party->setCreated(true);
        $party->setSentdate(new \DateTime());

        /** @var Participant[] $participants */
        $participants = [];
        for ($i = 1; $i <= 5; ++$i) {
            $participant = new Participant();
            if ($i === 1) {
                $participant->setPartyAdmin(true);
            }

            $participant->setName('test'.$i);
            $participant->setEmail('test'.$i.'@test.com');
            $participant->setParty($party);

            if ($i === 1) {
                $participant->setUrl(FeatureContext::PARTICIPANT_URL_TOKEN);
            }

            if ($i === 1) {
                $wishlistItem = new WishlistItem();

                $wishlistItem
                    ->setDescription('Item 1')
                    ->setRank(1)
                    ->setParticipant($participant);

                $wishlistItem2 = new WishlistItem();

                $wishlistItem2
                    ->setDescription('Item 2')
                    ->setRank(2)
                    ->setParticipant($participant);

                $participant->setWishlistItems([$wishlistItem, $wishlistItem2]);
            }

            if ($i === 2) {
                // Add a item to the second participants wishlist
                $wishlistItem = new WishlistItem();

                $wishlistItem
                    ->setDescription('World peace')
                    ->setRank(1)
                    ->setParticipant($participant);

                $participant->setWishlistItems([$wishlistItem]);
            }

            $participants[] = $participant;
        }

        $participants[0]->setAssignedParticipant($participants[1]);
        $participants[1]->setAssignedParticipant($participants[0]);
        $participants[2]->setAssignedParticipant($participants[4]);
        $participants[3]->setAssignedParticipant($participants[2]);
        $participants[4]->setAssignedParticipant($participants[3]);

        foreach ($participants as $participant) {
            $party->addParticipant($participant);
        }

        $manager->persist($party);
        $manager->flush();
    }

    private function loadCreatedParty(ObjectManager $manager)
    {
        // Party created by the administrator but not started yet
        $party = new Party(false);

        $party->setListurl(FeatureContext::CREATED_PARTY_URL_TOKEN);
        $party->setWishlistsurl(FeatureContext::CREATED_PARTY_WISHLISTS_URL_TOKEN);
        $party->setAmount(90);

        $eventDate = (new \DateTime())->add(new \DateInterval('P1M14D'));

        $party->setEventdate($eventDate);
        $party->setLocation('Test location');
        $party->setMessage('Test message');

        for ($i = 1; $i <= 5; ++$i) {
            $participant = new Participant();
            if ($i === 1) {
                $participant->setPartyAdmin(true);
            }

            $participant->setName('test'.$i);
            $participant->setEmail('test'.$i.'@test.com');
            $participant->setParty($party);

            $party->addParticipant($participant);
        }

        $manager->persist($party);
        $manager->flush();
    }
}
