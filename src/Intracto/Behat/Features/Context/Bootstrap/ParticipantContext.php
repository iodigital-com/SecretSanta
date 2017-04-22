<?php

namespace Intracto\Behat\Features\Context\Bootstrap;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Intracto\Behat\Features\Context\FeatureContext;
use Intracto\Behat\Services\JQueryHelper;
use Webmozart\Assert\Assert;

class ParticipantContext extends RawMinkContext
{
    use KernelDictionary;

    /**
     * @Given /^I am on a participant page$/
     */
    public function iAmOnAParticipantPage()
    {
        $path = $this->getContainer()->get('router')->generate('participant_view', ['url' => FeatureContext::PARTICIPANT_URL_TOKEN]);
        $this->visitPath($path);
    }

    /**
     * @Then /^I should see my secret santa$/
     */
    public function iShouldSeeMySecretSanta()
    {
        $node = $this->getSession()->getPage()->find('css', '.yoursecretsant > .yourgift');

        Assert::true(null !== $node, 'Can\'t find your secret santa on the page');
        Assert::eq($node->getText(), 'test2', 'Incorrect matched secret santa found');
    }

    /**
     * @Then /^I should see the wishlist of my secret santa$/
     */
    public function iShouldSeeTheWishlistOfMySecretSanta()
    {
        $wishlist = $this->getSession()->getPage()->find('css', '.my-secretsanta ul.wishlist');
        $wishlistItems = $this->getSession()->getPage()->findAll('css', '.my-secretsanta ul.wishlist li.wishlistitem');

        Assert::true(null !== $wishlist, 'Can\'t find the wishlist of your secret santa on the page');
        Assert::eq(count($wishlistItems), 1, 'Incorrect amount of wishlist items found');
        Assert::eq($wishlistItems[0]->getText(), 'World peace', 'Incorrect wishlist item found');
    }

    /**
     * @When /^I send a secret message$/
     */
    public function iSendASecretMessage()
    {
        $this->getSession()->getPage()->find('css', '#messagePanel')->click();

        JQueryHelper::scrollIntoView($this->getSession(), 'collapsedMessage');

        $this->getSession()->getPage()->find('css', '#anonymous_message_form_message')->setValue('Test message');

        $this->getSession()->getPage()->find('css', '#btn_send_anon_message')->click();
    }
}
