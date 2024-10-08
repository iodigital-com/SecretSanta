<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleListener
{
    public function __construct(private readonly array $supportedLocales)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        // Extract the first segment of the path to check for the locale
        $segments = explode('/', trim($path, '/'));
        $firstSegment = $segments[0] ?? null;

        if ((null === $firstSegment || (!in_array($firstSegment, $this->supportedLocales, true))) && !str_starts_with($firstSegment, '_')) {
            $defaultLocale = 'en';
            $request->setLocale($defaultLocale);
            $response = new RedirectResponse('/'.$defaultLocale.$path);
            $event->setResponse($response);
        }
    }
}
