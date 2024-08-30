<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Participant;
use App\Entity\Party;
use App\Mailer\MailerService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddParticipantFormHandler
{
    public function __construct(private TranslatorInterface $translator, private RequestStack $requestStack, private EntityManagerInterface $em, private MailerService $mailerService)
    {}

	/**
	 * @throws TransportExceptionInterface
	 */
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

        if ($party->getCreated()) {
            $this->em->persist($newParticipant);

			/** @var ParticipantRepository $participantRepository */
			$participantRepository = $this->em->getRepository(Participant::class);

            /**
             * Find participant that hasn't retrieved match yet. If none found, use admin.
             *
             * @var Participant|null $linkParticipant
             */
            $linkParticipant = $participantRepository->findOneUnseenByPartyId($party->getId());
            if ($linkParticipant === null) {
                // use party admin instead
                $linkParticipant = $participantRepository->findAdminByPartyId($party->getId());
            }

            $linkMatch = $linkParticipant->getAssignedParticipant();
            $linkParticipant->setAssignedParticipant($newParticipant);

            $this->em->persist($linkParticipant);
            $this->em->flush();

            $newParticipant->setAssignedParticipant($linkMatch);
            $this->em->persist($newParticipant);

            // Flush all changes
            $this->em->flush();

            $this->mailerService->sendSecretSantaMailForParticipant($newParticipant);
        } else {
            $this->em->persist($newParticipant);
            $this->em->flush();
        }

		$session->getFlashBag()->add('success', $this->translator->trans('flashes.management.add_participant.success'));
    }
}
