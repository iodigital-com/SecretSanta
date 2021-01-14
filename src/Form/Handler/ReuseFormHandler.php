<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Mailer\MailerService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReuseFormHandler
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

        if ($this->mailer->sendReuseLinksMail($data['email'])) {
            $this->session->getFlashBag()->add('success', $this->translator->trans('flashes.reuse.success'));
        } else {
            $this->session->getFlashBag()->add('danger', $this->translator->trans('flashes.reuse.error'));
        }

        return true;
    }
}
