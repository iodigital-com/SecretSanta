<?php

namespace Intracto\SecretSantaBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RecaptchaExtension extends AbstractExtension
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
            new TwigFunction('getRecaptchaSecrets', [$this, 'getRecaptchaSecrets']),
        ];
    }

    public function getRecaptchaSecrets()
    {
        return $this->captchaSecrets;
    }
}
