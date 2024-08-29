<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Entity\Participant;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Mailer\MailerService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendMessageFormHandler
{
	private RequestStack $requestStack;

    /** @var TranslatorInterface */
    private $translator;

    /** @var EntityManager */
    private $em;

    /** @var MailerService */
    private $mailerService;

    public function __construct(RequestStack $requestStack, TranslatorInterface $translator, EntityManagerInterface $em, MailerService $mailerService)
    {
		$this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->em = $em;
        $this->mailerService = $mailerService;
    }

    public function handle(FormInterface $form, Request $request): bool
    {
        if (!$form->handleRequest($request)->isValid()) {
			$this->requestStack->getSession()->getFlashBag()->add('danger', $this->translator->trans('participant_communication-send_message.feedback.error_in_form'));

            return false;
        }

        $message = $form->getData()['message'];
        $senderId = $form->getData()['participant'];

        $sender = $this->em->getRepository(Participant::class)->findOneBy(['url' => $senderId]);
        if (null === $sender) {
			$this->requestStack->getSession()->getFlashBag()->add('danger', $this->translator->trans('participant_communication-send_message.feedback.error'));

            return false;
        }

        $this->mailerService->sendAnonymousMessage($sender->getAssignedParticipant(), $message);

		$this->requestStack->getSession()->getFlashBag()->add('success', $this->translator->trans('participant_communication-send_message.feedback.success'));

        return true;
    }
}
