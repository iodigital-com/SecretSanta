<?php

declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Intracto\SecretSantaBundle\Entity\Party;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class PartyFormHandler
{
    private EntityManagerInterface $em;
    private MailerService $mailer;

    public function __construct(EntityManagerInterface $em, MailerService $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function handle(FormInterface $form, Request $request): bool
    {
        /** @var Party $party */
        $party = $form->getData();

        if (!$request->isMethod('POST')) {
            return false;
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

        $this->mailer->sendPendingConfirmationMail($party);

        return true;
    }
}
