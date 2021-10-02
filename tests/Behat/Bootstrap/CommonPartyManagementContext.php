<?php

namespace App\Tests\Behat\Bootstrap;

use App\Tests\Behat\ContainerAwareContextTrait;
use Behat\MinkExtension\Context\RawMinkContext;
use App\Tests\Behat\Service\JQueryHelper;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Webmozart\Assert\Assert;

class CommonPartyManagementContext extends RawMinkContext implements ContainerAwareInterface
{
    use ContainerAwareContextTrait;

    /**
     * @When /^I edit the first participant name to ([^"]*)$/
     */
    public function iEditTheFirstParticipantNameToAdmin($participantName)
    {
        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr.participant.owner .participant-edit-icon')->click();

        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr.participant.owner .input_edit_name')->setValue($participantName);

        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr.participant.owner .save-edit')->click();

        JQueryHelper::waitForAsynchronousActionsToFinish($this->getSession());
    }

    /**
     * @Given /^I edit the first participant email to ([^"]*)$/
     */
    public function iEditTheFirstParticipantEmail($email)
    {
        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr.participant.owner .participant-edit-icon')->click();

        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr.participant.owner .input_edit_email')->setValue($email);

        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr.participant.owner .save-edit')->click();

        JQueryHelper::waitForAsynchronousActionsToFinish($this->getSession());
    }

    /**
     * @Then /^the name of the first participant should be ([^"]*)$/
     */
    public function theFirstParticipantNameShouldBeAdmin($expectedParticipantName)
    {
        $participantName = $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr:first-child > td.participant-name')->getText();

        Assert::eq($participantName, $expectedParticipantName, 'Participant name was not changed');
    }

    /**
     * @Then /^the email address of the first participant should be ([^"]*)$/
     */
    public function theFirstParticipantEmailShouldBeChanged($expectedParticipantEmail)
    {
        $participantEmail = $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr:first-child > td.participant-email')->getText();

        Assert::eq($participantEmail, $expectedParticipantEmail, 'Participant email was not changed');
    }

    /**
     * @Given /^I should see a (?P<type>[^"]*) message$/
     * @Given /^I should see an (?P<type>[^"]*) message$/
     */
    public function iShouldSeeAWarning($type)
    {
        if ($type === 'error') {
            $type = 'danger';
        }

        $node = $this->getSession()->getPage()->find('css', sprintf('.box > .alert-%s', $type));

        Assert::true(null !== $node);
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
     * @When /^I update the party location to ([^"]*)$/
     */
    public function iUpdateThePartyLocationToTheOffice($location)
    {
        $this->getSession()->getPage()->find('css', '#btn_update')->click();

        JQueryHelper::scrollIntoView($this->getSession(), 'update-party-details');

        $this->getSession()->getPage()->find('css', '#update-party-details #update_party_details_location')->setValue($location);

        $this->getSession()->getPage()->find('css', '#btn_update_confirmation')->click();
    }

    /**
     * @Then /^the summary location info should be ([^"]*)$/
     */
    public function theSummaryLocationInfoShouldBeTheCompanyOffice($location)
    {
        $text = $this->getSession()->getPage()->find('css', '#partyDetails li.party-location')->getText();

        Assert::contains($text, $location, 'The location was not changed');
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

        //Make sure the form is scrolled into view (small screens)
        JQueryHelper::scrollIntoView($this->getSession(), 'add-participant');

        $this->getSession()->getPage()->find('css', '#add_participant_name')->setValue('test6');
        $this->getSession()->getPage()->find('css', '#add_participant_email')->setValue('test6@example.com');

        $this->getSession()->getPage()->find('css', '#btn_add_confirmation')->click();
    }

    /**
     * @Then /^I should have (\d+) participants$/
     */
    public function iShouldHaveParticipants($expectedParticipantCount)
    {
        $nodes = $this->getSession()->getPage()->findAll('css', '#mysanta > tbody > tr');

        Assert::eq(count($nodes), $expectedParticipantCount, 'Incorrect participant count');
    }

    /**
     * @When /^I remove a participant$/
     */
    public function iRemoveAParticipant()
    {
        $this->getSession()->getPage()->find('css', '#mysanta > tbody > tr:last-child .btn.link_remove_participant')->click();

        $this->getSession()->getPage()->find('css', '#btn_remove_participant_confirmation')->click();
    }
}
