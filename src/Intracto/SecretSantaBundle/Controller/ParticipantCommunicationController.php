<?php

declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Form\Handler\SendMessageFormHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Form\Type\AnonymousMessageFormType;

class ParticipantCommunicationController extends AbstractController
{
    /**
     * @Route("/participant-communication/send-message", name="participant_communication_send_message")
     * @Method("POST")
     */
    public function sendMessageAction(Request $request, SendMessageFormHandler $handler)
    {
        $form = $this->createForm(AnonymousMessageFormType::class);

        $handler->handle($form, $request);

        return $this->redirectToRoute('participant_view', ['url' => $form->getData()['participant']]);
    }
}
