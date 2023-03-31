<?php

namespace App\Twig;

use GeoIp2\Database\Reader;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GeoCountryExtension extends AbstractExtension
{
    public function __construct(
        private string $geoIpDbPath,
        private RequestStack $requestStack,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('geoCountry', [$this, 'geoCountryFunction']),
        ];
    }

    public function geoCountryFunction()
    {
        $geoCountry = '';
        $request = $this->requestStack->getCurrentRequest();

        // Eg: we are running in a command
        if (null === $request) {
            return $geoCountry;
        }

        $reader = new Reader($this->geoIpDbPath);
        try {
            $geoInformation = $reader->city($request->getClientIp());
            $geoCountry = $geoInformation->country->isoCode;
        } catch (\Exception) {}

        return $geoCountry;
    }
}
