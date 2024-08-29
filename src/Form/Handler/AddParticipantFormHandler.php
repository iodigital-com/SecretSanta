<?php

declare(strict_types=1);

namespace App\Form\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Participant;
use App\Entity\Party;
use App\Mailer\MailerService;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddParticipantFormHandler
{
    private TranslatorInterface $translator;
	private RequestStack $requestStack;
    private EntityManager $em;
    private MailerService $mailer;

    public function __construct(TranslatorInterface $translator, RequestStack $requestStack, EntityManagerInterface $em, MailerService $mailerService)
    {
        $this->translator = $translator;
		$this->requestStack = $requestStack;
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
			$this->requestStack->getSession()->getFlashBag()->add('danger', $this->translator->trans('flashes.management.add_participant.danger'));

            return;
        }

        $newParticipant->setParty($party);

        if ($party->getCreated()) {
            $this->em->persist($newParticipant);

            /**
             * Find participant that hasn't retrieved match yet. If none found, use admin.
             *
             * @var Participant|null $linkParticipant
             */
            $linkParticipant = $this->em->getRepository(Participant::class)->findOneUnseenByPartyId($party->getId());
            if ($linkParticipant === null) {
                // use party admin instead
                $linkParticipant = $this->em->getRepository(Participant::class)->findAdminByPartyId($party->getId());
            }

            $linkMatch = $linkParticipant->getAssignedParticipant();
            $linkParticipant->setAssignedParticipant($newParticipant);

            $this->em->persist($linkParticipant);
            $this->em->flush();

            $newParticipant->setAssignedParticipant($linkMatch);
            $this->em->persist($newParticipant);

            // Flush all changes
            $this->em->flush();

            $this->mailer->sendSecretSantaMailForParticipant($newParticipant);
        } else {
            $this->em->persist($newParticipant);
            $this->em->flush();
        }

		$this->requestStack->getSession()->getFlashBag()->add('success', $this->translator->trans('flashes.management.add_participant.success'));
    }
}
