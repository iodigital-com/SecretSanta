<?php

namespace Intracto\BehatBundle\Page;

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
        if (stristr($headerText, 'Exclude')) {
            return true;
        }

        return false;
    }

    public function confirmExcludes()
    {
        $this->find('css', '.btn-create-event')->click();

        return $this->getPage('Pool Created');
    }
}
