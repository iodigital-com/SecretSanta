<?php

namespace Intracto\Behat\Features\Context\Bootstrap;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Element\NodeElement;
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

    /**
     * @Given /^I add an item "([^"]*)" to my wishlist$/
     */
    public function iAddAnItemToMyWishlist($wishlistItem)
    {
        $this->getSession()->getPage()->find('css', '.add-new-participant')->click();

        $this->getSession()->getPage()->find('css', 'table.wishlist-items > tbody > tr:last-child .wishlistitem-description')->setValue($wishlistItem);
        $this->getSession()->getPage()->find('css', 'table.wishlist-items > tbody > tr:last-child .save-new-participant')->click();

        JQueryHelper::waitForAsynchronousActionsToFinish($this->getSession());
    }

    /**
     * @Given /^I remove an item from my wishlist$/
     */
    public function iRemoveAnItemFromMyWishlist()
    {
        $this->getSession()->getPage()->find('css', 'table.wishlist-items > tbody > tr:first-child .btn.remove-participant')->click();

        JQueryHelper::waitForAsynchronousActionsToFinish($this->getSession());
    }

    /**
     * @Then /^I should have (\d+) items on my wishlist$/
     */
    public function iShouldHaveItemsOnMyWishlist($expectedWishlistItemCount)
    {
        /** @var NodeElement[] $wishlistItems */
        $wishlistItems = $this->getSession()->getPage()->findAll('css', 'table.wishlist-items > tbody > tr');

        if ($expectedWishlistItemCount == 0) {
            //Workaround for fix in commit a68d6a8. This if should be removed when the workaround is removed
            Assert::eq(count($wishlistItems), 1, 'Incorrect wishlist item count');

            $lastWishlistItemValue = $wishlistItems[0]->find('css', 'input.wishlistitem-description')->getValue();

            Assert::isEmpty($lastWishlistItemValue, 'The last wishlist item value is not empty');
        } else {
            Assert::eq(count($wishlistItems), $expectedWishlistItemCount, 'Incorrect wishlist item count');
        }
    }

    /**
     * @Then /^I should have an item "([^"]*)" on my wishlist$/
     */
    public function iShouldHaveAnItemOnMyWishlist($expectedItemName)
    {
        $wishlistItems = $this->getSession()->getPage()->findAll('css', 'table.wishlist-items > tbody > tr');

        $itemFound = false;

        /** @var NodeElement $item */
        foreach ($wishlistItems as $item) {
            $currentValue = $item->find('css', 'td:nth-child(2) > input')->getValue();

            if ($currentValue === $expectedItemName) {
                $itemFound = true;
                break;
            }
        }

        Assert::true($itemFound, 'Wishlist item is not found');
    }
}
