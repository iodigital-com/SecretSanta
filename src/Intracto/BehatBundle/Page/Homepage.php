<?php

namespace Intracto\BehatBundle\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class Homepage extends Page
{
    //TODO: use inline elements instead of $this->find()
    protected $elements = [
        'Button extra participant' => ['css' => '.add-btn-create.add-new-participant'],
        'Participant table rows' => ['css' => '.participants.table > tbody > tr.participant'],
    ];

    public function createParty($participants, $location, $amount, $eventDate)
    {
        if (count($participants) > 3) {
            //We need to add extra lines to the participant form
            $extraLineCount = count($participants) - 3;

            while ($extraLineCount > 0) {
                $this->find('css', '.add-btn-create.add-new-participant')->click();

                --$extraLineCount;
            }
        }

        $tableRows = $this->findAll('css', '.participants.table > tbody > tr.participant');

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

        $this->find('css', '#party_eventdate')->setValue($eventDate);
        $this->find('css', '#party_location')->setValue($location);
        $this->find('css', '#party_amount')->setValue($amount);

        $this->find('css', '.btn-create-event')->click();

        return $this->getPage('Party Created');
    }
}
