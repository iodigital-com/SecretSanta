<?php

namespace Intracto\Behat\Features\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

class FeatureContext extends RawMinkContext
{
    use KernelDictionary;

    const STARTED_PARTY_URL_TOKEN = 'jux1a80pnu8s48ko8ckskco08s0wc4g';
    const STARTED_PARTY_WISHLISTS_URL_TOKEN = 'k81skwgcsu8c0x48apc8n80ujo4kso0';
    const CREATED_PARTY_URL_TOKEN = 'mup6g475ok08ss8gc4wgg8ogcwkw08s';
    const CREATED_PARTY_WISHLISTS_URL_TOKEN = 'p6gssu7kw45g4gom0ggws8cok0c8w88';

    const PARTICIPANT_URL_TOKEN = 'hkn7ycdnqhskg4g8g0c08wkkgsoscws';

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    /**
     * @BeforeScenario @fixtures
     */
    public function loadFixtures()
    {
        $loader = new ContainerAwareLoader($this->getContainer());
        $loader->loadFromDirectory(__DIR__.'/../../DataFixtures');
        $executor = new ORMExecutor($this->getEntityManager());
        $executor->execute($loader->getFixtures(), true);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @When /^I refresh the page$/
     */
    public function iRefreshThePage()
    {
        $this->getSession()->reload();
    }
}
