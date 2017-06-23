<?php

namespace Intracto\Behat\Features\Context\Bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Intracto\Behat\Features\Context\FeatureContext;

class UnstartedPartyManagementContext extends RawMinkContext
{
    use KernelDictionary;

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
