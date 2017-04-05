<?php

namespace Intracto\SecretSantaBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestInfoLogger
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
}
