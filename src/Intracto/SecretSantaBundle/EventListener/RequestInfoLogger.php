<?php

namespace Intracto\SecretSantaBundle\EventListener;

use Monolog\Logger;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestInfoLogger
{
    /** @var \Monolog\Logger */
    private $logger;
    /** @var \Symfony\Component\HttpFoundation\Request */
    private $requestStack;

    public function __construct(Logger $logger, RequestStack $requestStack)
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
