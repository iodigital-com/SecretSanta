<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Entity\Participant;
use App\Service\UnsubscribeService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

class UnsubscribeFormHandler
{
    public function __construct(private TranslatorInterface $translator, private RequestStack $requestStack, private UnsubscribeService $unsubscribeService)
    {}

    public function handle(FormInterface $form, Request $request, Participant $participant): bool
    {
        if (!$request->isMethod('POST')) {
            return false;
        }

		/** @var Session $session */
		$session = $this->requestStack->getSession();

        if (!$form->handleRequest($request)->isValid()) {
			$session->getFlashBag()->add('danger', $this->translator->trans('participant_unsubscribe.feedback.error'));

            return false;
        }

        $unsubscribeData = $form->getData();

        if (false === $unsubscribeData['blacklist'] && false === $unsubscribeData['allParties']) {
			$session->getFlashBag()->add('danger', $this->translator->trans('participant_unsubscribe.feedback.error_atleast_one_option'));

            return false;
        }

        if ($unsubscribeData['blacklist']) {
            $this->unsubscribeService->blacklist($participant, $request->getClientIp());
        } else {
            $this->unsubscribeService->unsubscribe($participant, $unsubscribeData['allParties']);
        }

		$session->getFlashBag()->add('success', $this->translator->trans('participant_unsubscribe.feedback.success'));

        return true;
    }
}
