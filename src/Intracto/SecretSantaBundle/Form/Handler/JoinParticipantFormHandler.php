<?php

declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Entity\Party;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

class JoinParticipantFormHandler
{
    private TranslatorInterface $translator;
    private Session $session;
    private EntityManager $em;

    public function __construct(TranslatorInterface $translator, SessionInterface $session, EntityManagerInterface $em)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->em = $em;
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

        $this->em->persist($newParticipant);
        $this->em->flush();

        $this->session->getFlashBag()->add('success', $this->translator->trans('flashes.management.add_participant.success'));
    }
}
