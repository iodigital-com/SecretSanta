<?php
namespace Intracto\SecretSantaBundle\Features\Context\Bootstrap;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\RawMinkContext;
use Webmozart\Assert\Assert;

/**
 * Class LanguageContext.
 */
class LanguageContext extends RawMinkContext
{
    /**
     * @When /^I click on language selector and I choose ([^"]*)$/
     *
     * @param string $arg1
     */
    public function iClickOnLanguageSelectorAndIChooseLanguage(string $arg1)
    {
        $this->getSession()->getPage()
            ->find('css', '.lang__selection select')
            ->selectOption($arg1);
    }

    /**
     * @Then /^I should see the site in ([^"]*)$/
     *
     * @param string $arg1
     *
     * @return bool
     */
    public function iShouldSeeTheSiteIn(string $arg1)
    {
        $selected = trim($this->getSession()->getPage()
                ->find('css', '.lang__selection select option:selected')
                ->getText());

        Assert::true(
            $selected == $arg1,
            'The site has not been changed to the language ' .$arg1
        );
    }
}