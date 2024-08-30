<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Mailer\MailerService;
use App\Model\ContactSubmission;
use App\Service\RecaptchaService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactFormHandler
{
    public function __construct(private TranslatorInterface $translator, private RequestStack $requestStack, private MailerService $mailer, private RecaptchaService $recaptchaService)
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

        /** @var ContactSubmission $data */
        $data = $form->getData();
        $captchaResult = $this->recaptchaService->validateRecaptchaToken($data->getRecaptchaToken());

		/** @var Session $session */
		$session = $this->requestStack->getSession();

        // Client succeed recaptcha validation.
        if ($captchaResult['success'] !== true) {
			$session->getFlashBag()->add('danger', 'You seem like a robot ('.current($captchaResult['error-codes']).'), sorry.');

            return false;
        }

        $mailResult = $this->mailer->sendContactFormEmail($data);

        if (!$mailResult) {
			$session->getFlashBag()->add('danger', 'Mail was not sent due to unknown error.');
            return false;
        }

		$session->getFlashBag()->add('success', $this->translator->trans('flashes.contact.success'));

        return true;
    }
}
