<?php

namespace Intracto\Behat\Features\Context\Bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use Intracto\Behat\Page\Homepage;
use Intracto\Behat\Page\ParticipantExclude;
use Intracto\Behat\Page\PartyCreated;
use Webmozart\Assert\Assert;

class PartyContext extends RawMinkContext
{
    /**
     * @var array
     */
    private $participants;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $partyDate;

    /**
     * @var int
     */
    private $memberCount;

    /**
     * @var Homepage
     */
    private $homepage;

    /**
     * @var ParticipantExclude
     */
    private $participantExcludePage;

    /**
     * @var PartyCreated
     */
    private $partyCreatedPage;

    /**
     * @param Homepage $homepage
     */
    public function __construct(Homepage $homepage)
    {
        $this->homepage = $homepage;
    }

    /**
     * @When /^(?:|I) create a party with (?P<memberCount>[0-9]+) participants$/
     */
    public function setupParty($memberCount)
    {
        $this->memberCount = $memberCount;

        $i = 0;
        while ($i < $this->memberCount) {
            $this->participants[] = ['name' => 'test'.$i, 'email' => 'test'.$i.'@test.com'];

            ++$i;
        }
    }

    /**
     * @When /^(?:|I) choose a location$/
     */
    public function setLocation()
    {
        $this->location = 'Intracto';
    }

    /**
     * @When /^(?:|I) choose the amount to spend$/
     */
    public function setAmount()
    {
        $this->amount = rand(10, 100).' Euro';
    }

    /**
     * @When /^(?:|I) choose a party date in the future$/
     */
    public function setPartyDate()
    {
        $currentDate = new \DateTime();
        $partyDate = $currentDate->add(new \DateInterval('P2M'));

        $this->partyDate = $partyDate->format('d-m-Y');
    }

    /**
     * @When /^(?:|I) create the party$/
     */
    public function createParty()
    {
        $this->partyCreatedPage = $this->homepage->createParty($this->participants, $this->location, $this->amount, $this->partyDate);
    }

    /**
     * @Then I should get a confirmation
     */
    public function confirmationPage()
    {
        Assert::true(
            $this->partyCreatedPage->hasConfirmationHeader(),
            'The confirmaton text could not be found on the page'
        );
    }

    /**
     * @Then /^(?:|I) should be able to exclude participant combinations$/
     */
    public function excludePage()
    {
        Assert::true(
            $this->participantExcludePage->hasExcludeHeader(),
            'The exclude header could not be found on the page'
        );
    }

    /**
     * @When /^(?:|I) confirm the excludes and create the party$/
     */
    public function confirmExcludesCreateParty()
    {
        $this->partyCreatedPage = $this->participantExcludePage->confirmExcludes();
    }
}
