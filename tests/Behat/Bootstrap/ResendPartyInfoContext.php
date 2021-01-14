<?php

namespace App\Tests\Behat\Bootstrap;

use App\Tests\Behat\ContainerAwareContextTrait;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class ResendPartyInfoContext extends RawMinkContext implements ContainerAwareInterface
{
    use ContainerAwareContextTrait;

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
