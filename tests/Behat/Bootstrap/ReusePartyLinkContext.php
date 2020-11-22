<?php

namespace App\Tests\Behat\Bootstrap;

use App\Tests\Behat\ContainerAwareContextTrait;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class ReusePartyLinkContext extends RawMinkContext implements ContainerAwareInterface
{
    use ContainerAwareContextTrait;

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
