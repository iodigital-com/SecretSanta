<?php

namespace App\Tests\Behat\Bootstrap;

use App\Tests\Behat\ContainerAwareContextTrait;
use Behat\MinkExtension\Context\RawMinkContext;
use App\Tests\Behat\FeatureContext;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class UnsubscribeContext extends RawMinkContext implements ContainerAwareInterface
{
    use ContainerAwareContextTrait;

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
