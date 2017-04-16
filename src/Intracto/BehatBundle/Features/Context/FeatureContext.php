<?php

namespace Intracto\BehatBundle\Features\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Entity\Party;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext extends RawMinkContext implements KernelAwareContext
{
    const PARTY_URL_TOKEN = 'jux1a80pnu8s48ko8ckskco08s0wc4g';

    /**
     * @var KernelInterface
     */
    private $kernel;

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $purger = new ORMPurger($this->getContainer()->get('doctrine')->getManager());
        $purger->purge();
    }

    /**
     * @BeforeScenario @fixtures
     */
    public function loadFixtures()
    {
        $party = new Party(false);

        $party->setListurl(self::PARTY_URL_TOKEN);
        $party->setAmount(100);

        $eventDate = (new \DateTime())->add(new \DateInterval('P2M'));

        $party->setEventdate($eventDate);
        $party->setLocation('Test');
        $party->setMessage('Test message');
        $party->setCreated(true);

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

        $this->getEntityManager()->persist($party);

        $this->getEntityManager()->flush();

        $this->getContainer()->get('intracto_secret_santa.service.participant')->shuffleParticipants($party);

        $this->getEntityManager()->flush();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
