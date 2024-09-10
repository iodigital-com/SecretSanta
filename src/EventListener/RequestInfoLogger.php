<?php

declare(strict_types=1);

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestInfoLogger implements EventSubscriberInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var RequestStack */
    private $requestStack;

    public function __construct(LoggerInterface $logger, RequestStack $requestStack)
    {
        $this->logger = $logger;
        $this->requestStack = $requestStack;
    }

    public function onKernelException()
    {
        if (PHP_SAPI !== 'cli') {
            $this->logger->debug('GET values '.serialize($this->requestStack->getMainRequest()->query->all()));
            $this->logger->debug('POST values '.serialize($this->requestStack->getMainRequest()->request->all()));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
