<?php

namespace Intracto\Behat\Services;

use Behat\Mink\Session;

abstract class JQueryHelper
{
    /**
     * @param Session $session
     */
    public static function waitForAsynchronousActionsToFinish(Session $session)
    {
        $session->wait(5000, '0 === jQuery.active');
    }

    public static function scrollIntoView(Session $session, $elementId)
    {
        $function = <<<JS
(function(){
  var elem = document.getElementById("$elementId");
  elem.scrollIntoView(false);
})()
JS;
        try {
            $session->executeScript($function);
        }
        catch(\Exception $e) {
            throw new \Exception(sprintf('ScrollIntoView failed: "%s"', $e->getMessage()));
        }
    }
}
