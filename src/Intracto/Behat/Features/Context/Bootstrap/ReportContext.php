<?php

declare(strict_types=1);

namespace Intracto\Behat\Features\Context\Bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Webmozart\Assert\Assert;

class ReportContext extends RawMinkContext
{
    use KernelDictionary;

    /**
     * @Given /^I am on the report page$/
     */
    public function iAmOnTheReportPage()
    {
        $path = $this->getContainer()->get('router')->generate('report');
        $this->visitPath($path);
    }

    /**
     * @Then /^I should see the report page$/
     */
    public function iShouldSeeTheReportPage()
    {
        $node = $this->getSession()->getPage()->find('css', '.box > h1');

        Assert::true(null !== $node, 'Report');
    }
}
