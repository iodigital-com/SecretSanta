<?php

namespace Intracto\Behat\Features\Context\Bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Intracto\Behat\Features\Context\FeatureContext;

class UnsubscribeContext extends RawMinkContext
{
    use KernelDictionary;

    /**
     * @Given /^I am on the unsubscribe page$/
     */
    public function iAmOnTheUnsubscribePage()
    {
        $path = $this->getContainer()->get('router')->generate('unsubscribe_confirm', ['url' => FeatureContext::PARTICIPANT_URL_TOKEN]);
        $this->visitPath($path);
    }

    /**
     * @When /^I want to unsubscribe from all parties$/
     */
    public function iWantToAddMyselfToTheBlacklist()
    {
        $this->getSession()->getPage()->find('css', '#unsubscribe_allParties')->click();

        $this->getSession()->getPage()->find('css', '#unsubscribe_button')->click();
    }

    /**
     * @When /^I submit the form with selecting an option$/
     */
    public function iSubmitTheFormWithSelectingAnOption()
    {
        $this->getSession()->getPage()->find('css', '#unsubscribe_button')->click();
    }
}
