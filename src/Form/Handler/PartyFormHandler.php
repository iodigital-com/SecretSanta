<?php

declare(strict_types=1);

namespace App\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Party;
use App\Mailer\MailerService;
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
        $party->setCreatedFromIp((string) $request->getClientIp());

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

        $party->setOwnerName($party->getParticipants()[0]->getName());
        $party->setOwnerEmail($party->getParticipants()[0]->getEmail());

        $party->setCreationDate(new \DateTime());
        $party->setLocale($request->getLocale());

        $this->em->persist($party);
        $this->em->flush();

        $this->mailer->sendPendingConfirmationMail($party);

        return true;
    }
}
