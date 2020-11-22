<?php

declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Entity\Party;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AddParticipantFormHandler
{
    private TranslatorInterface $translator;
    private Session $session;
    private EntityManager $em;
    private MailerService $mailer;

    public function __construct(TranslatorInterface $translator, SessionInterface $session, EntityManagerInterface $em, MailerService $mailerService)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->em = $em;
        $this->mailer = $mailerService;
    }

    public function handle(FormInterface $form, Request $request, Party $party): void
    {
        /** @var Participant $newParticipant */
        $newParticipant = $form->getData();

        if (!$request->isMethod('POST')) {
            return;
        }

        if (!$form->handleRequest($request)->isValid()) {
            $this->session->getFlashBag()->add('danger', $this->translator->trans('flashes.management.add_participant.danger'));

            return;
        }

        $newParticipant->setParty($party);

        if ($party->getCreated()) {
            $this->em->persist($newParticipant);

            /** @var Participant $admin */
            $admin = $this->em->getRepository('IntractoSecretSantaBundle:Participant')->findAdminByPartyId($party->getId());

            $adminMatch = $admin->getAssignedParticipant();
            $admin->setAssignedParticipant($newParticipant);

            $this->em->persist($admin);
            $this->em->flush();

            $newParticipant->setAssignedParticipant($adminMatch);
            $this->em->persist($newParticipant);

            // Flush all changes
            $this->em->flush();

            $this->mailer->sendSecretSantaMailForParticipant($admin);
            $this->mailer->sendSecretSantaMailForParticipant($newParticipant);
        } else {
            $this->em->persist($newParticipant);
            $this->em->flush();
        }

        $this->session->getFlashBag()->add('success', $this->translator->trans('flashes.management.add_participant.success'));
    }
}
