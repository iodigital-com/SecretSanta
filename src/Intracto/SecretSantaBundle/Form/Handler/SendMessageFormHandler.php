<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

class SendMessageFormHandler
{
    /** @var Session */
    private $session;

    /** @var TranslatorInterface */
    private $translator;

    /** @var EntityManager */
    private $em;

    /** @var MailerService */
    private $mailerService;

    public function __construct(SessionInterface $session, TranslatorInterface $translator, EntityManagerInterface $em, MailerService $mailerService)
    {
        $this->session = $session;
        $this->translator = $translator;
        $this->em = $em;
        $this->mailerService = $mailerService;
    }

    public function handle(FormInterface $form, Request $request) : bool
    {
        if (!$form->handleRequest($request)->isValid()) {
            $this->session->getFlashBag()->add('danger', $this->translator->trans('participant_communication-send_message.feedback.error_in_form'));

            return false;
        }

        $message = $form->getData()['message'];
        $senderId = $form->getData()['participant'];

        $sender = $this->em->getRepository('IntractoSecretSantaBundle:Participant')->findOneBy(['url' => $senderId]);
        if (null === $sender) {
            $this->session->getFlashBag()->add('danger', $this->translator->trans('participant_communication-send_message.feedback.error'));

            return false;
        }

        $this->mailerService->sendAnonymousMessage($sender->getAssignedParticipant(), $message);

        $this->session->getFlashBag()->add('success', $this->translator->trans('participant_communication-send_message.feedback.success'));

        return true;
    }
}
