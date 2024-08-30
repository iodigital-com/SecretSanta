<?php

declare(strict_types=1);

namespace App\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Participant;
use App\Entity\Party;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

class JoinParticipantFormHandler
{
    public function __construct(private TranslatorInterface $translator, private RequestStack $requestStack, private EntityManagerInterface $em)
    {}

    public function handle(FormInterface $form, Request $request, Party $party): void
    {
        /** @var Participant $newParticipant */
        $newParticipant = $form->getData();

        if (!$request->isMethod('POST')) {
            return;
        }

		/** @var Session $session */
		$session = $this->requestStack->getSession();

        if (!$form->handleRequest($request)->isValid()) {
			$session->getFlashBag()->add('danger', $this->translator->trans('flashes.management.add_participant.danger'));

            return;
        }

        $newParticipant->setParty($party);

        $this->em->persist($newParticipant);
        $this->em->flush();

		$session->getFlashBag()->add('success', $this->translator->trans('flashes.management.add_participant.success'));
    }
}
