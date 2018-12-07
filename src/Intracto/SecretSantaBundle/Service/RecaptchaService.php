<?php

namespace Intracto\SecretSantaBundle\Service;

use ReCaptcha\ReCaptcha;

class RecaptchaService
{

    public function __construct()
    {

    }

    /**
     * @param string $token
     *
     * @return array
     */
    public function validateRecaptchaToken(string $token): array
    {
        $recaptcha = new ReCaptcha('6LcCY38UAAAAAMXtsofuXSnt2PBQLeegZCWDrRCo');

        $resp = $recaptcha
            ->setExpectedHostname($_SERVER['SERVER_NAME'])
            ->setExpectedAction('contact')
            ->setScoreThreshold(0.5)
            ->verify($token, $_SERVER['REMOTE_ADDR']);

        return $resp->toArray();
    }
}
