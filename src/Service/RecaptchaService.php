<?php

namespace App\Service;

use ReCaptcha\ReCaptcha;

class RecaptchaService
{
    /** @var \stdClass */
    private $recaptchaSecret;

    public function __construct(string $recaptchaSecret)
    {
        $this->recaptchaSecret = json_decode(file_get_contents($recaptchaSecret));
    }

    public function validateRecaptchaToken(string $token): array
    {
        $recaptcha = new ReCaptcha($this->recaptchaSecret->secret_key);

        $resp = $recaptcha
            ->setExpectedHostname($_SERVER['SERVER_NAME'])
            ->setExpectedAction($this->recaptchaSecret->action)
            ->setScoreThreshold($this->recaptchaSecret->threshold)
            ->verify($token, $_SERVER['REMOTE_ADDR']);

        return $resp->toArray();
    }
}
