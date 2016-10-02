<?php

namespace Intracto\SecretSantaBundle\Features\Context\Bootstrap;

use Behat\MinkExtension\Context\MinkContext;
use Intracto\SecretSantaBundle\Behat\Page\Homepage;
use Intracto\SecretSantaBundle\Behat\Page\ParticipantExclude;
use Intracto\SecretSantaBundle\Behat\Page\PoolCreated;
use Webmozart\Assert\Assert;

class PoolContext extends MinkContext
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
    private $eventDate;

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
     * @var PoolCreated
     */
    private $poolCreatedPage;

    /**
     * @param Homepage $homepage
     */
    public function __construct(Homepage $homepage)
    {
        $this->homepage = $homepage;
    }

    /**
     * @When /^(?:|I) create an event with (?P<memberCount>[0-9]+) participants$/
     */
    public function setupPool($memberCount)
    {
        $this->memberCount = $memberCount;

        $i = 0;
        while ($i < $this->memberCount) {
            //TODO: use faker to get random input
            $this->participants[] = ['name' => 'test' . $i, 'email' => 'test'.$i.'@test.com'];

            ++$i;
        }
    }

    /**
     * @When /^(?:|I) choose a location$/
     */
    public function setLocation()
    {
        //TODO: replace with faker generated value
        $this->location = 'Intracto';
    }

    /**
     * @When /^(?:|I) choose the amount to spend$/
     */
    public function setAmount()
    {
        //TODO: replace with faker generated value
        $this->amount = rand(10, 100) . ' Euro';
    }

    /**
     * @When /^(?:|I) choose an event date in the future$/
     */
    public function setEventDate()
    {
        $currentDate = new \DateTime();
        $eventDate = $currentDate->add(new \DateInterval('P2M'));

        $this->eventDate = $eventDate->format('d-m-y');
    }

    /**
     * @When /^(?:|I) create the event$/
     */
    public function createEvent()
    {
        $resultPage = $this->homepage->createEvent($this->participants, $this->location, $this->amount, $this->eventDate);

        if ($this->memberCount > 3) {
            $this->participantExcludePage = $resultPage;
        } else {
            $this->poolCreatedPage = $resultPage;
        }
    }

    /**
     * @Then I should get a confirmation
     */
    public function confirmationPage()
    {
        Assert::true(
            $this->poolCreatedPage->hasConfirmationHeader(),
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
    public function confirmExcludesCreatePool()
    {
        $this->poolCreatedPage = $this->participantExcludePage->confirmExcludes();
    }
}
