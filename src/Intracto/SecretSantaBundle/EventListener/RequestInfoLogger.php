<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestInfoLogger implements EventSubscriberInterface
{
    /** @var LoggerInterface */
    private $logger;
    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    public function __construct(LoggerInterface $logger, RequestStack $requestStack)
    {
        $this->logger = $logger;
        $this->requestStack = $requestStack;
    }

    public function onKernelException()
    {
        if (PHP_SAPI !== 'cli') {
            $this->logger->debug('GET values '.serialize($this->requestStack->getMasterRequest()->query->all()));
            $this->logger->debug('POST values '.serialize($this->requestStack->getMasterRequest()->request->all()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
