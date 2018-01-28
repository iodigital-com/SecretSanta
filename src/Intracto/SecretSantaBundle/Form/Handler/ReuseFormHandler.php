<?php

declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Form\Handler;

use Intracto\SecretSantaBundle\Mailer\MailerService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ReuseFormHandler
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

        if ($this->mailer->sendReuseLinksMail($data['email'])) {
            $this->session->getFlashBag()->add('success', $this->translator->trans('flashes.reuse.success'));
        } else {
            $this->session->getFlashBag()->add('danger', $this->translator->trans('flashes.reuse.error'));
        }

        return true;
    }
}
