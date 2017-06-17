<?php

namespace Intracto\Behat\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class ParticipantExclude extends Page
{
    public function hasExcludeHeader()
    {
        $element = $this->find('css', '.box > h1');

        if (!$element) {
            return false;
        }

        $headerText = $element->getText();

        return false !== stripos($headerText, 'Exclude');
    }

    public function confirmExcludes()
    {
        $this->find('css', '.btn-create-event')->click();

        return $this->getPage('Party Created');
    }
}
