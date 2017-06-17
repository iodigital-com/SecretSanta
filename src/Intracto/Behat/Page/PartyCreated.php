<?php

namespace Intracto\Behat\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class PartyCreated extends Page
{
    public function hasConfirmationHeader()
    {
        $element = $this->find('css', '.box > h1');

        if (!$element) {
            return false;
        }

        $headerText = $element->getText();

        return false !== stripos($headerText, 'Validate your participation');
    }
}
