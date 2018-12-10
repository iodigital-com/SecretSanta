<?php

namespace Intracto\Behat\Features\Context\Bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Intracto\Behat\Features\Context\FeatureContext;
use Webmozart\Assert\Assert;

class StartedPartyManagementContext extends RawMinkContext
{
    use KernelDictionary;

    /**
     * @Given /^I am on the started party management page$/
     */
    public function goToStartedPartyManagementPage()
    {
        $path = $this->getContainer()->get('router')->generate('party_manage', ['listurl' => FeatureContext::STARTED_PARTY_URL_TOKEN]);
        $this->visitPath($path);
    }

    /**
     * @When /^I remove the second participant$/
     */
    public function iRemoveTheSecondParticipant()
    {
        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr:nth-child(2) .btn.link_remove_participant')->click();

        $this->getSession()->getPage()->find('css', '#btn_remove_participant_confirmation')->click();
    }

    /**
     * @When /^I send a party update$/
     */
    public function iSendAPartyUpdate()
    {
        $this->getSession()->getPage()->find('css', '#btn_send_party_update')->click();
    }

    /**
     * @When /^I view all matches$/
     */
    public function iViewAllMatches()
    {
        $this->getSession()->getPage()->find('css', '#btn_expose_matches')->click();
    }

    /**
     * @Then /^I should see (\d+) participants with their matches$/
     */
    public function iShouldSeeParticipantsWithTheirMatches($expectedParticipantCount)
    {
        $nodes = $this->getSession()->getPage()->findAll('css', '#all_matches > tbody > tr');

        Assert::eq(count($nodes), $expectedParticipantCount, 'Incorrect participant count');
    }

    /**
     * @When /^I view all whishlists$/
     */
    public function iViewAllWhishlists()
    {
        $this->getSession()->getPage()->find('css', '#btn_expose_wishlists')->click();
    }

    /**
     * @Then /^I should see (\d+) participants with their wishlists$/
     */
    public function iShouldSeeParticipantsWithTheirWishlists($expectedParticipantCount)
    {
        $this->getSession()->wait(5000);

        $participants = $this->getSession()->getPage()->findAll('css', '.container h3.participant-name');
        $wistlists = $this->getSession()->getPage()->findAll('css', '.container .wishlist');

        Assert::eq(count($participants), $expectedParticipantCount, 'Incorrect participant count');
        Assert::eq(count($wistlists), $expectedParticipantCount, 'Incorrect wishlist count');
    }

    /**
     * @Given /^I resend the email for the first participant$/
     */
    public function iResendTheEmailForTheFirstParticipant()
    {
        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr.participant.owner .btn-participant-resend')->click();
    }
}
