<?php

namespace Intracto\SecretSantaBundle\EventListener;

use JMS\I18nRoutingBundle\Router\I18nRouter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var array $availableLocals
     */
    private $availableLocals;

    /**
     * @var I18nRouter $router
     */
    private $router;

    public function __construct($availableLocals, I18nRouter $router)
    {
        $this->availableLocals = $availableLocals;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $route = $request->get('_route');
        $locale = $request->get('_locale');
        $route_params = $request->attributes->get('_route_params');

        //menu locale switcher used?
        if ($request->query->has('force-locale-switch')) {
            $request->cookies->set('hl', $locale);
            $url = $this->router->generate($route, ['_locale' => $locale] + $route_params);
            $event->setResponse(new RedirectResponse($url));
            return;
        }
        //first request the cookie is empty. Gets auto set by I18nRouter
        if ($request->cookies->get('hl')) {
            return;
        }
        $preferredLocale = $request->getPreferredLanguage($this->availableLocals);
        if ($preferredLocale && $request->attributes->get('_locale') != $preferredLocale) {
            $url = $this->router->generate($route, ['_locale' => $preferredLocale] + $route_params);
            $event->setResponse(new RedirectResponse($url));
        }


    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 1)),
        );
    }
}