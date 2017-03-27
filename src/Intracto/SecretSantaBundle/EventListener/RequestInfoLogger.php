<?php

namespace Intracto/SecretSantaBundle;

use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;

class RequestInfoLogger
{
    /** @var \Monolog\Logger */
    private $logger;
    /** @var \Symfony\Component\HttpFoundation\Request */
    private $request;

    public function __construct(Logger $logger, Request $request)
    {
        $this->logger = $logger;
        $this->request = $request;
    }

    public function onKernelException()
    {
        if (PHP_SAPI !== 'cli') {
            $this->logger->debug('GET values ' . serialize($this->request->query->all()));
            $this->logger->debug('POST values ' . serialize($this->request->request->all()));
        }
    }
}
