<?php

declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Form\Handler;

use Intracto\SecretSantaBundle\Mailer\MailerService;
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

    /**
     * @param TranslatorInterface $translator
     * @param Session             $session
     * @param MailerService       $mailer
     */
    public function __construct(TranslatorInterface $translator, SessionInterface $session, MailerService $mailer)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->mailer = $mailer;
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

        $data = $form->getData();

        if ($this->mailer->sendContactFormEmail($data)) {
            $this->translator->setLocale($request->getLocale());
            $this->session->getFlashBag()->add('success', $this->translator->trans('flashes.contact.success'));
        }

        return true;
    }
}
