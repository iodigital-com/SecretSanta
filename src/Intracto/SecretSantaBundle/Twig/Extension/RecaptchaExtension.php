<?php

namespace Intracto\SecretSantaBundle\Twig\Extension;

class RecaptchaExtension extends \Twig_Extension
{
    /** @var array */
    private $captchaSecrets;

    public function __construct(string $recaptchaSecret)
    {
        $this->captchaSecrets = json_decode(file_get_contents($recaptchaSecret), true);
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getRecaptchaSecrets', [$this, 'getRecaptchaSecrets']),
        ];
    }

    public function getRecaptchaSecrets()
    {
        return $this->captchaSecrets;
    }
}
