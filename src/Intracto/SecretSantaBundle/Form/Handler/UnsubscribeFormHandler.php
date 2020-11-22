<?php

declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Form\Handler;

use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Service\UnsubscribeService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UnsubscribeFormHandler
{
    private TranslatorInterface $translator;
    private Session $session;
    private UnsubscribeService $unsubscribeService;

    public function __construct(TranslatorInterface $translator, SessionInterface $session, UnsubscribeService $unsubscribeService)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->unsubscribeService = $unsubscribeService;
    }

    public function handle(FormInterface $form, Request $request, Participant $participant): bool
    {
        if (!$request->isMethod('POST')) {
            return false;
        }

        if (!$form->handleRequest($request)->isValid()) {
            $this->session->getFlashBag()->add('danger', $this->translator->trans('participant_unsubscribe.feedback.error'));

            return false;
        }

        $unsubscribeData = $form->getData();

        if (false === $unsubscribeData['blacklist'] && false === $unsubscribeData['allParties']) {
            $this->session->getFlashBag()->add('danger', $this->translator->trans('participant_unsubscribe.feedback.error_atleast_one_option'));

            return false;
        }

        if ($unsubscribeData['blacklist']) {
            $this->unsubscribeService->blacklist($participant, $request->getClientIp());
        } else {
            $this->unsubscribeService->unsubscribe($participant, $unsubscribeData['allParties']);
        }

        $this->session->getFlashBag()->add('success', $this->translator->trans('participant_unsubscribe.feedback.success'));

        return true;
    }
}
