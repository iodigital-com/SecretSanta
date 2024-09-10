<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Entity\Participant;
use App\Mailer\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendMessageFormHandler
{
    public function __construct(private RequestStack $requestStack, private TranslatorInterface $translator, private EntityManagerInterface $em, private MailerService $mailerService)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function handle(FormInterface $form, Request $request): bool
    {
        /** @var Session $session */
        $session = $this->requestStack->getSession();

        if (!$form->handleRequest($request)->isValid()) {
            $session->getFlashBag()->add('danger', $this->translator->trans('participant_communication-send_message.feedback.error_in_form'));

            return false;
        }

        $message = $form->getData()['message'];
        $senderId = $form->getData()['participant'];

        $sender = $this->em->getRepository(Participant::class)->findOneBy(['url' => $senderId]);
        if (null === $sender) {
            $session->getFlashBag()->add('danger', $this->translator->trans('participant_communication-send_message.feedback.error'));

            return false;
        }

        $this->mailerService->sendAnonymousMessage($sender->getAssignedParticipant(), $message);

        $session->getFlashBag()->add('success', $this->translator->trans('participant_communication-send_message.feedback.success'));

        return true;
    }
}
