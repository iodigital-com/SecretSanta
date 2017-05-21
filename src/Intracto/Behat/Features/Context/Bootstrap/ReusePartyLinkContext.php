<?php

namespace Intracto\Behat\Features\Context\Bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

class ReusePartyLinkContext extends RawMinkContext
{
    use KernelDictionary;

    /**
     * @Given /^I am on a reuse party link page$/
     */
    public function iAmOnAReusePartyLinkPage()
    {
        $path = $this->getContainer()->get('router')->generate('request_reuse_url');
        $this->visitPath($path);
    }

    /**
     * @When /^I request the reuse info for "([^"]*)"$/
     */
    public function iRequestTheReuseInfoFor($email)
    {
        $this->getSession()->getPage()->find('css', '#request_reuse_url_email')->setValue($email);

        $this->getSession()->getPage()->find('css', '#request_reuse_url_submit')->click();
    }
}
