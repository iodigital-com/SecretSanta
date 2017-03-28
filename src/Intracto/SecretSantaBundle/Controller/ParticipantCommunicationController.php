<?php

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Form\Type\AnonymousMessageFormType;

class ParticipantCommunicationController extends Controller
{
    /**
     * @Route("/participant-communication/send-message", name="participant_communication_send_message")
     * @Method("POST")
     */
    public function sendMessageAction(Request $request)
    {
        $messageForm = $this->createForm(AnonymousMessageFormType::class);

        $messageForm->handleRequest($request);
        $url = $messageForm->getData()['participant'];
        if ($messageForm->isValid()) {
            $message = $messageForm->getData()['message'];
            $recipientId = $messageForm->getData()['recipient'];

            $em = $this->get('doctrine.orm.entity_manager');
            $recipient = $em->getRepository('IntractoSecretSantaBundle:Participant')->find($recipientId);

            if (count($recipient) == 1) {
                $this->get('intracto_secret_santa.mail')->sendAnonymousMessage($recipient, $message);
                $feedback = [
                        'type' => 'success',
                        'message' => $this->get('translator')->trans('participant_communication-send_message.feedback.success'),
                    ];
            } else {
                $feedback = [
                        'type' => 'danger',
                        'message' => $this->get('translator')->trans('participant_communication-send_message.feedback.error'),
                    ];
            }

            $this->addFlash($feedback['type'], $feedback['message']);
        } else {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('participant_communication-send_message.feedback.error_in_form')
            );
        }

        return $this->redirect($this->generateUrl('participant_view', ['url' => $url]));
    }
}
