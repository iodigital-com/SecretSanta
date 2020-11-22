<?php

namespace App\Tests\Behat\Bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use App\Services\JQueryHelper;
use Webmozart\Assert\Assert;

class PartyContext extends RawMinkContext
{
    /**
     * @When /^(?:|I) create a party with (?P<memberCount>[0-9]+) participants$/
     */
    public function setupParty($memberCount)
    {
        $i = 0;
        $participants = [];
        while ($i < $memberCount) {
            $participants[] = ['name' => 'test'.$i, 'email' => 'test'.$i.'@example.com'];

            ++$i;
        }

        if (count($participants) > 3) {
            // We need to add extra lines to the participant form
            $extraLineCount = count($participants) - 3;

            while ($extraLineCount > 0) {
                $this->getSession()->getPage()->find('css', 'button.add-new-participant')->click();

                --$extraLineCount;
            }
        }

        $tableRows = $this->getSession()->getPage()->findAll('css', '.participants.table > tbody > tr.participant');

        $i = 0;
        foreach ($tableRows as $tableRow) {
            /* @var $tableRow \Behat\Mink\Element\NodeElement */
            $participantName = $tableRow->find('css', '.participant-name');
            $participantMail = $tableRow->find('css', '.participant-mail');

            if ($participantName) {
                $participantName->setValue($participants[$i]['name']);
                $participantMail->setValue($participants[$i]['email']);

                ++$i;
            }
        }
    }

    /**
     * @When /^(?:|I) choose a location$/
     */
    public function setLocation()
    {
        $this->getSession()->getPage()->find('css', '#party_location')->setValue('Intracto');
    }

    /**
     * @When /^(?:|I) choose the amount to spend$/
     */
    public function setAmount()
    {
        $this->getSession()->getPage()->find('css', '#party_amount')->setValue('10 Euro');
    }

    /**
     * @When /^(?:|I) choose a party date in the future$/
     */
    public function setPartyDate()
    {
        $currentDate = new \DateTime();
        $partyDate = $currentDate->add(new \DateInterval('P2M'));

        $this->getSession()->getPage()->find('css', '#party_eventdate')->setValue($partyDate->format('d-m-Y'));
    }

    /**
     * @When /^(?:|I) confirm the opt-in$/
     */
    public function confirmTheOptIn()
    {
        $this->getSession()->getPage()->find('css', '#party_confirmed')->check();
    }

    /**
     * @When /^(?:|I) create the party$/
     */
    public function createParty()
    {
        JQueryHelper::waitForAsynchronousActionsToFinish($this->getSession());

        JQueryHelper::scrollIntoView($this->getSession(), 'create-party-btn');

        $this->getSession()->getPage()->find('css', '.btn-create-event')->click();
    }

    /**
     * @Then I should get a confirmation
     */
    public function confirmationPage()
    {
        $element = $this->getSession()->getPage()->find('css', 'div.box > h1');

        Assert::eq($element->getText(), 'Only 1 step to go! - Validate your participation', 'The confirmaton text could not be found on the page');
    }

    /**
     * @Given /^I add a csv of data for (\d+) participants$/
     */
    public function iAddACsvOfDataForParticipants($participantCount)
    {
        if ($participantCount <= 0) {
            throw new \LogicException('Invalid participant count');
        }

        $i = 0;
        $csvData = "Name,Mailaddress\r\n";
        while ($participantCount > 0) {
            $csvData .= "test{$i},test{$i}@example.com\r\n";
            ++$i;
            --$participantCount;
        }

        $this->getSession()->getPage()->find('css', 'button.add-import-participant')->click();

        JQueryHelper::scrollIntoView($this->getSession(), 'importCSV');

        $this->getSession()->getPage()->find('css', 'textarea#importCSV')->setValue($csvData);
        $this->getSession()->getPage()->find('css', 'button.add-import-participant-do')->click();
        $this->getSession()->wait(500);
    }

    /**
     * @Then /^I should have a form with (\d+) participants$/
     */
    public function iShouldHaveAFormWithParticipants($expectedParticipantCount)
    {
        $nodes = $this->getSession()->getPage()->findAll('css', 'table.participants > tbody > tr.participant');

        Assert::eq(count($nodes), $expectedParticipantCount, 'Incorrect participant count '.count($nodes));
    }
}
