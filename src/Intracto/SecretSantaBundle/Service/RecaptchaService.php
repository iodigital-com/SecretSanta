<?php

namespace Intracto\SecretSantaBundle\Service;

use ReCaptcha\ReCaptcha;

class RecaptchaService
{
    /** @var \stdClass */
    private $recaptchaSecret;

    /**
     * @param string $recaptchaSecret
     */
    public function __construct(string $recaptchaSecret)
    {
        $this->recaptchaSecret = json_decode(file_get_contents($recaptchaSecret));
    }

    /**
     * @param string $token
     *
     * @return array
     */
    public function validateRecaptchaToken(string $token): array
    {
        $recaptcha = new ReCaptcha($this->recaptchaSecret->secret_key);

        $resp = $recaptcha
            ->setExpectedHostname($_SERVER['SERVER_NAME'])
            ->setExpectedAction($this->recaptchaSecret->action)
            ->setScoreThreshold($this->recaptchaSecret->treshold)
            ->verify($token, $_SERVER['REMOTE_ADDR']);

        return $resp->toArray();
    }
}
