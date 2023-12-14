<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Mailer\MailerService;
use App\Model\ContactSubmission;
use App\Service\RecaptchaService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactFormHandler
{
    private TranslatorInterface $translator;
    private Session $session;
    private MailerService $mailer;
    private RecaptchaService $recaptcha;

    public function __construct(TranslatorInterface $translator, SessionInterface $session, MailerService $mailer, RecaptchaService $recaptchaService)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->recaptcha = $recaptchaService;
    }

    public function handle(FormInterface $form, Request $request): bool
    {
        if (!$request->isMethod('POST')) {
            return false;
        }

        if (!$form->handleRequest($request)->isValid()) {
            return false;
        }

        /** @var ContactSubmission $data */
        $data = $form->getData();
        $captchaResult = $this->recaptcha->validateRecaptchaToken($data->getRecaptchaToken());

        // Client succeed recaptcha validation.
        if ($captchaResult['success'] !== true) {
            $this->session->getFlashBag()->add('danger', 'You seem like a robot, sorry.');
            foreach ($captchaResult['error-codes'] as $errorCode) {
                $this->session->getFlashBag()->add('danger', '  - '.$errorCode);
            }

            return false;
        }

        $mailResult = $this->mailer->sendContactFormEmail($data);

        if (!$mailResult) {
            $this->session->getFlashBag()->add('danger', 'Mail was not sent due to unknown error.');
            return false;
        }

        $this->translator->setLocale($request->getLocale());
        $this->session->getFlashBag()->add('success', $this->translator->trans('flashes.contact.success'));

        return true;
    }
}
