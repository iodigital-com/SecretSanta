<?php

namespace Intracto\Behat\Features\Context\Bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

class ResendPartyInfoContext extends RawMinkContext
{
    use KernelDictionary;

    /**
     * @Given /^I am on a resend party info page$/
     */
    public function iAmOnAResendPartyInfoPage()
    {
        $path = $this->getContainer()->get('router')->generate('forgot_url');
        $this->visitPath($path);
    }

    /**
     * @When /^I request the party info for "([^"]*)"$/
     */
    public function iRequestThePartyInfoFor($email)
    {
        $this->getSession()->getPage()->find('css', '#forgot_link_email')->setValue($email);

        $this->getSession()->getPage()->find('css', '#forgot_link_submit')->click();
    }
}
