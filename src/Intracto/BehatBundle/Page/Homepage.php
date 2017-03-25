<?php

namespace Intracto\BehatBundle\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class Homepage extends Page
{
    //TODO: use inline elements instead of $this->find()
    protected $elements = [
        'Button extra participant' => ['css' => '.add-btn-create.add-new-entry'],
        'Participant table rows' => ['css' => '.entries.table > tbody > tr.entry'],
    ];

    public function createEvent($participants, $location, $amount, $eventDate)
    {
        if (count($participants) > 3) {
            //We need to add extra lines to the participant form
            $extraLineCount = count($participants) - 3;

            while ($extraLineCount > 0) {
                $this->find('css', '.add-btn-create.add-new-entry')->click();

                --$extraLineCount;
            }
        }

        $tableRows = $this->findAll('css', '.entries.table > tbody > tr.entry');

        $i = 0;
        foreach ($tableRows as $tableRow) {
            /* @var $tableRow \Behat\Mink\Element\NodeElement */
            $entryName = $tableRow->find('css', '.entry-name');
            $entryMail = $tableRow->find('css', '.entry-mail');

            if ($entryName) {
                $entryName->setValue($participants[$i]['name']);
                $entryMail->setValue($participants[$i]['email']);

                ++$i;
            }
        }

        $this->find('css', '#party_eventdate')->setValue($eventDate);
        $this->find('css', '#party_location')->setValue($location);
        $this->find('css', '#party_amount')->setValue($amount);

        $this->find('css', '.btn-create-event')->click();

        if (count($participants) > 3) {
            return $this->getPage('Participant Exclude');
        } else {
            return $this->getPage('Pool Created');
        }
    }
}
