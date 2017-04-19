<?php

namespace Intracto\BehatBundle\Features\Context\Bootstrap;

use Intracto\BehatBundle\Features\Context\FeatureContext;
use Webmozart\Assert\Assert;

class PartyManagementContext extends FeatureContext
{
    /**
     * @Given /^(?:|I) am on the party management page$/
     */
    public function goToPartyManagementPage()
    {
        $path = $this->getContainer()->get('router')->generate('party_manage', ['listUrl' => FeatureContext::PARTY_URL_TOKEN]);
        $this->visitPath($path);
    }

    /**
     * @Then /^I should see the participant list$/
     */
    public function iShouldSeeTheParticipantList()
    {
        $node = $this->getSession()->getPage()->find('css', '#mysanta');

        Assert::true(null !== $node, 'The participant list is not visible on the page');
    }

    /**
     * @When /^I delete the party$/
     */
    public function iDeleteTheParty()
    {
        $this->getSession()->getPage()->find('css', '#btn_delete')->click();

        $this->getSession()->getPage()->find('css', '#delete-confirmation')->setValue('delete everything');
        $this->getSession()->getPage()->find('css', '#btn_delete_confirmation')->click();
    }

    /**
     * @Then /^I should see the delete confirmation$/
     */
    public function iShouldSeeTheDeleteConfirmation()
    {
        $header = $this->getSession()->getPage()->find('css', '.box > h1')->getText();

        Assert::true('Your Secret Santa list was deleted!' === $header, 'Delete confirmation header can not be found');
    }

    /**
     * @When /^I add a participant$/
     */
    public function iAddAParticipantToTheParty()
    {
        $this->getSession()->getPage()->find('css', '#btn_add')->click();
        $this->getSession()->getPage()->find('css', '#add_participant_name')->setValue('test6');
        $this->getSession()->getPage()->find('css', '#add_participant_email')->setValue('test6@test.com');

        $this->getSession()->getPage()->find('css', '#btn_add_confirmation')->click();

    }

    /**
     * @Then /^I should have (\d+) participants$/
     */
    public function iShouldHaveParticipants($expectedParticipantCount)
    {
        $nodes = $this->getSession()->getPage()->findAll('css', '#mysanta > tbody > tr');

        Assert::eq($expectedParticipantCount, count($nodes));
    }

    /**
     * @When /^I remove a participant$/
     */
    public function iRemoveAParticipant()
    {
        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr:last-child .btn.link_remove_participant')->click();

        $this->getSession()->getPage()->find('css', '#btn_remove_participant_confirmation')->click();
    }

    /**
     * @When /^I remove the party admin$/
     */
    public function iRemoveTheAdmin()
    {
        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr:first-child .btn.link_remove_participant')->click();

        $this->getSession()->getPage()->find('css', '#btn_remove_participant_confirmation')->click();
    }

    /**
     * @Given /^I should see a warning$/
     */
    public function iShouldSeeAWarning()
    {
        $node = $this->getSession()->getPage()->find('css', '.box > .alert-warning');

        Assert::true(null !== $node);
    }
}
