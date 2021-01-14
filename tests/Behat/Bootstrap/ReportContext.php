<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bootstrap;

use App\Tests\Behat\ContainerAwareContextTrait;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Webmozart\Assert\Assert;

class ReportContext extends RawMinkContext implements ContainerAwareInterface
{
    use ContainerAwareContextTrait;

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
