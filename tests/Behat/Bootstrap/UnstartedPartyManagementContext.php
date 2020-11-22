<?php

namespace App\Tests\Behat\Bootstrap;

use App\Tests\Behat\ContainerAwareContextTrait;
use Behat\MinkExtension\Context\RawMinkContext;
use App\Tests\Behat\FeatureContext;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class UnstartedPartyManagementContext extends RawMinkContext implements ContainerAwareInterface
{
    use ContainerAwareContextTrait;

    /**
     * @Given /^I am on the party management page$/
     */
    public function goToPartyManagementPage()
    {
        $path = $this->getContainer()->get('router')->generate('party_manage', ['listurl' => FeatureContext::CREATED_PARTY_URL_TOKEN]);
        $this->visitPath($path);
    }

    /**
     * @When /^I start the party$/
     */
    public function iStartTheParty()
    {
        $this->getSession()->getPage()->find('css', 'a.btn-create-party')->click();
    }
}
