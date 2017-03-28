<?php

namespace Intracto\BehatBundle\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class PoolCreated extends Page
{
    public function hasConfirmationHeader()
    {
        $element = $this->find('css', '.box > h1');

        if (!$element) {
            return false;
        }

        $headerText = $element->getText();

        if (stristr($headerText, 'Validate your participation')) {
            return true;
        }

        return false;
    }
}
