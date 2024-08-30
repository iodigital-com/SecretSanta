<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Mailer\MailerService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ForgotUrlFormHandler
{
    public function __construct(private TranslatorInterface $translator, private RequestStack $requestStack, private MailerService $mailer)
    {}

	/**
	 * @throws TransportExceptionInterface
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

		/** @var Session $session */
		$session = $this->requestStack->getSession();

        if ($this->mailer->sendForgotLinkMail($data['email'])) {
			$session->getFlashBag()->add('success', $this->translator->trans('flashes.forgot_url.success'));
        } else {
			$session->getFlashBag()->add('danger', $this->translator->trans('flashes.forgot_url.error'));
        }

        return true;
    }
}
