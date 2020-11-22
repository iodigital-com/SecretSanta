<?php

declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Form\Handler;

use Intracto\SecretSantaBundle\Mailer\MailerService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ForgotUrlFormHandler
{
    private TranslatorInterface $translator;
    private Session $session;
    private MailerService $mailer;

    public function __construct(TranslatorInterface $translator, SessionInterface $session, MailerService $mailer)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->mailer = $mailer;
    }

    public function handle(FormInterface $form, Request $request): bool
    {
        if (!$request->isMethod('POST')) {
            return false;
        }

        if (!$form->handleRequest($request)->isValid()) {
            return false;
        }

        $data = $form->getData();

        if ($this->mailer->sendForgotLinkMail($data['email'])) {
            $this->session->getFlashBag()->add('success', $this->translator->trans('flashes.forgot_url.success'));
        } else {
            $this->session->getFlashBag()->add('danger', $this->translator->trans('flashes.forgot_url.error'));
        }

        return true;
    }
}
