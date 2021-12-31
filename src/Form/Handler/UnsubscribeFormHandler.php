<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Entity\Participant;
use App\Service\UnsubscribeService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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

        $unsubscribeOption = $form->getData()['unsubscribeOption'];

        switch ($unsubscribeOption) {
            case 'current':
                $this->unsubscribeService->unsubscribe($participant, false);
                break;

            case 'all':
                $this->unsubscribeService->unsubscribe($participant, true);
                break;

            case 'blacklist':
                $this->unsubscribeService->blacklist($participant, $request->getClientIp());

            default:
                return false;
        }

        $this->session->getFlashBag()->add('success', $this->translator->trans('participant_unsubscribe.feedback.success'));

        return true;
    }
}
