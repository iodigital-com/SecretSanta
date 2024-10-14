<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Handler\SendMessageFormHandler;
use App\Form\Type\AnonymousMessageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ParticipantCommunicationController extends AbstractController
{
    #[Route('/{_locale}/participant-communication/send-message', name: 'participant_communication_send_message', methods: ['POST'])]
    public function sendMessageAction(Request $request, SendMessageFormHandler $handler): RedirectResponse
    {
        $form = $this->createForm(AnonymousMessageFormType::class);

        $handler->handle($form, $request);

        return $this->redirectToRoute('participant_view', ['url' => $form->getData()['participant']]);
    }
}
