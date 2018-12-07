<?php

declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Form\Handler;

use Intracto\SecretSantaBundle\Mailer\MailerService;
use Intracto\SecretSantaBundle\Model\ContactSubmission;
use Intracto\SecretSantaBundle\Service\RecaptchaService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ContactFormHandler
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var MailerService
     */
    private $mailer;

    /** @var RecaptchaService */
    private $recaptcha;

    /**
     * @param TranslatorInterface $translator
     * @param SessionInterface    $session
     * @param MailerService       $mailer
     * @param RecaptchaService    $recaptchaService
     */
    public function __construct(TranslatorInterface $translator, SessionInterface $session, MailerService $mailer, RecaptchaService $recaptchaService)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->recaptcha = $recaptchaService;
    }

    /**
     * @param FormInterface $form
     * @param Request       $request
     *
     * @return bool
     */
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

        $result = $this->recaptcha->validateRecaptchaToken($data->getRecaptchaToken());

        // Client succeed recaptcha validation.
        if ($result['success']) {
            if ($this->mailer->sendContactFormEmail($data)) {
                $this->translator->setLocale($request->getLocale());
                $this->session->getFlashBag()->add('success', $this->translator->trans('flashes.contact.success'));
            }
        }

        return true;
    }
}
