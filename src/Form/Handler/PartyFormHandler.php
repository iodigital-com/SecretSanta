<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Entity\Party;
use App\Form\Handler\Exception\RateLimitExceededException;
use App\Mailer\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class PartyFormHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerService $mailer,
        private CacheInterface $cache,
    ) {
    }

    public function handle(FormInterface $form, Request $request, bool $ignoreRateLimit = false): bool
    {
        /** @var Party $party */
        $party = $form->getData();
        $party->setCreatedFromIp((string) $request->getClientIp());

        $rateLimitCacheKey = md5($request->getClientIp());

        if (!$request->isMethod('POST')) {
            return false;
        }

        $rateLimitCacheItem = 0;
        try {
            $rateLimitCacheItem = $this->cache->get($rateLimitCacheKey);
        } catch (\Exception) {
            // No previous party found in rate limit cache
        }
        if ($rateLimitCacheItem > date('U') - 60) {
            throw new RateLimitExceededException();
        }

        if (!$form->handleRequest($request)->isValid()) {
            return false;
        }

        // Save party
        foreach ($party->getParticipants() as $participant) {
            $participant->setParty($party);
        }

        $party->setCreationDate(new \DateTime());
        $party->setLocale($request->getLocale());

        $this->em->persist($party);
        $this->em->flush();

        if(!$ignoreRateLimit) {
            $this->cache->set($rateLimitCacheKey, date('U'), 60);
        }

        $this->mailer->sendPendingConfirmationMail($party);

        return true;
    }
}
