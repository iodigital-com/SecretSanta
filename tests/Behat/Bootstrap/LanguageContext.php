<?php

namespace App\Tests\Behat\Bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use Webmozart\Assert\Assert;

class LanguageContext extends RawMinkContext
{
    /**
     * @When /^I click on language selector and I choose ([^"]*)$/
     */
    public function iClickOnLanguageSelectorAndIChooseLanguage(string $arg1): void
    {
        $this->getSession()->getPage()
            ->find('css', '.lang__selection select')
            ->selectOption($arg1);
    }

    /**
     * @Then /^I should see the site in ([^"]*)$/
     */
    public function iShouldSeeTheSiteIn(string $arg1): void
    {
        $selected = trim($this->getSession()->getPage()
                ->find('css', '.lang__selection select option:selected')
                ->getText());

        Assert::true(
            $selected == $arg1,
            'The site has not been changed to the language '.$arg1
        );
    }
}
